# Rule Vibe Coding
**Tech Stack:** Laravel 12 | MySQL | Inertia.js | Vue 3 | Tailwind CSS 4

---

## 📁 **1. Struktur Project**

### **Backend (Laravel)**
```
app/
├── Actions/           # Complex/reusable business operations ONLY
├── DTOs/             # Data Transfer Objects
├── Enums/            # Enum classes
├── Exceptions/       # Custom exceptions
├── Http/
│   ├── Controllers/  # Thin controllers (route handling)
│   ├── Requests/     # Form validation
│   ├── Resources/    # API resources
│   └── Middleware/   # Custom middleware
├── Models/           # Eloquent models
├── Services/         # Business logic layer
├── Observers/        # Model observers
└── Policies/         # Authorization logic

database/
├── factories/        # Model factories for testing
├── migrations/       # Database migrations
└── seeders/         # Database seeders

resources/
└── js/
    ├── Components/   # Reusable Vue components
    ├── Composables/  # Vue composables (reusable logic)
    ├── Layouts/      # Layout components
    ├── Pages/        # Inertia pages
    └── Utils/        # Helper functions

tests/
├── Feature/         # Feature/integration tests
└── Unit/           # Unit tests
```

### **❗ IMPORTANT: Architecture Decision Tree**
```
Apakah logic ini CRUD sederhana? 
├─ YES → Controller → Service → Model
└─ NO → Apakah logic ini reusable di banyak tempat?
    ├─ YES → Controller → Action → Service → Model
    └─ NO → Controller → Service → Model

❌ JANGAN buat Action untuk setiap operasi!
✅ Action hanya untuk: Payment processing, Report generation, Complex calculations
```

---

## 🎯 **2. Naming Convention**

### **Laravel**
- **Controllers:** `PascalCase` + `Controller` suffix
  ```php
  UserController, ProductCategoryController
  ```
- **Models:** `PascalCase` singular
  ```php
  User, ProductCategory, OrderItem
  ```
- **Migrations:** `snake_case` deskriptif
  ```php
  2024_01_01_create_users_table.php
  2024_01_02_add_status_to_orders_table.php
  ```
- **Routes:** `kebab-case`
  ```php
  /user-profile, /product-categories, /order-items
  ```
- **Variables/Methods:** `camelCase`
  ```php
  $userName, $isActive, getUserById()
  ```

### **Vue/JavaScript**
- **Components:** `PascalCase`
  ```javascript
  UserProfile.vue, ProductCard.vue, DataTable.vue
  ```
- **Composables:** `camelCase` with `use` prefix
  ```javascript
  useAuth.js, useModal.js, usePagination.js, useNotification.js
  ```
- **Props/Variables:** `camelCase`
  ```javascript
  userName, isLoading, productList
  ```

### **Database**
- **Tables:** `snake_case` plural
  ```sql
  users, product_categories, order_items
  ```
- **Pivot Tables:** `snake_case` alphabetical order
  ```sql
  product_tag, role_user (not user_role)
  ```
- **Columns:** `snake_case`
  ```sql
  first_name, created_at, is_active
  ```

---

## 🏗️ **3. Architecture Pattern**

### **Simple CRUD (90% of cases)**
```php
// Route
Route::resource('users', UserController::class);

// Controller - Thin, delegates to service
class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}
    
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->create($request->validated());
            
            return redirect()
                ->route('users.index')
                ->with('success', 'User created successfully');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create user');
        }
    }
    
    public function index(Request $request)
    {
        return Inertia::render('Users/Index', [
            'users' => $this->userService->paginate($request->all()),
            'filters' => $request->only(['search', 'status']),
        ]);
    }
}

// Service - Business logic
class UserService
{
    public function create(array $data): User
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            $user->assignRole('user');
            
            event(new UserCreated($user));
            
            DB::commit();
            return $user;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function paginate(array $filters)
    {
        return User::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->select('id', 'name', 'email', 'status', 'created_at')
            ->latest()
            ->paginate(20);
    }
}
```
### **Complex Operations (use actions)**
```php
// Action - Single complex operation
class ProcessPaymentAction
{
    public function __construct(
        private PaymentGateway $gateway,
        private InvoiceService $invoiceService,
        private NotificationService $notificationService
    ) {}
    
    public function execute(Order $order, array $paymentData): Payment
    {
        DB::beginTransaction();
        try {
            // Process payment
            $charge = $this->gateway->charge(
                $order->total,
                $paymentData['token']
            );
            
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $charge->amount,
                'status' => $charge->status,
                'transaction_id' => $charge->id,
            ]);
            
            // Update order
            $order->update(['status' => 'paid']);
            
            // Generate invoice
            $invoice = $this->invoiceService->generate($order);
            
            // Send notifications
            $this->notificationService->sendPaymentConfirmation(
                $order->user,
                $payment,
                $invoice
            );
            
            DB::commit();
            return $payment;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

// Controller
class PaymentController extends Controller
{
    public function store(
        Order $order,
        PaymentRequest $request,
        ProcessPaymentAction $action
    ) {
        try {
            $payment = $action->execute($order, $request->validated());
            
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Payment processed successfully');
                
        } catch (PaymentFailedException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
```

---

## ✅ **4. Laravel Best Practices**

### **Form Requests (Always use for validation)**
```php
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Use policies for complex authorization
        return Gate::allows('create', User::class);
    }
    
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah terdaftar dalam sistem',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ];
    }
    
    // Sanitize data before validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email),
            'name' => ucwords($this->name),
        ]);
    }
}
```

### **Eloquent Models**
```php
class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;
    
    // Mass assignment protection
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];
    
    // Hidden from JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    // Attribute casting
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'preferences' => 'array',
    ];
    
    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
    }
    
    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
    
    // Accessors & Mutators (Laravel 9+)
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value),
        );
    }
    
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }
    
    // Events (alternative: use Observers)
    protected static function booted(): void
    {
        static::creating(function ($user) {
            $user->uuid = Str::uuid();
        });
    }
}
```

### **Database Query Optimization**
```php
// ❌ BAD - N+1 Problem
$users = User::all();
foreach ($users as $user) {
    echo $user->orders->count(); // Extra query per user!
}

// ✅ GOOD - Eager Loading
$users = User::with('orders')->get();
foreach ($users as $user) {
    echo $user->orders->count(); // No extra queries
}

// ✅ GOOD - Eager Loading with Constraints
$users = User::with(['orders' => function ($query) {
    $query->where('status', 'completed')
          ->select('id', 'user_id', 'total', 'status');
}])->get();

// ✅ GOOD - Count without loading
$users = User::withCount('orders')->get();
// Access via: $user->orders_count

// ✅ GOOD - Select specific columns
User::select('id', 'name', 'email')->get();

// ✅ GOOD - Chunk for large datasets
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});

// ✅ GOOD - Lazy for memory efficiency
User::lazy()->each(function ($user) {
    // Process user with minimal memory
});

// ✅ GOOD - Use exists() instead of count()
if (User::where('email', $email)->exists()) {
    // User exists
}

// ❌ BAD
if (User::where('email', $email)->count() > 0) {
    // Slower
}
```
### **Database Transactions (Always for multi-step operations)**
```php
use Illuminate\Support\Facades\DB;

// ✅ GOOD - Manual transaction
DB::beginTransaction();
try {
    $user = User::create($userData);
    $user->profile()->create($profileData);
    $user->assignRole('user');
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}

// ✅ BETTER - Automatic transaction
DB::transaction(function () use ($userData, $profileData) {
    $user = User::create($userData);
    $user->profile()->create($profileData);
    $user->assignRole('user');
    return $user;
});

// Transaction with retry on deadlock
DB::transaction(function () {
    // Your code
}, 5); // Retry 5 times
```
### **Database Indexing Strategy**
```php
// Migration
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('order_number')->unique();
    $table->string('status');
    $table->decimal('total', 10, 2);
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes for frequently queried columns
    $table->index('status');
    $table->index('created_at');
    
    // Composite index for common query combinations
    $table->index(['user_id', 'status']);
    $table->index(['status', 'created_at']);
});

// Add index to existing table
Schema::table('users', function (Blueprint $table) {
    $table->index('email');
    $table->index(['status', 'created_at']);
});
```
---

## 🎨 **5. Inertia.js Pattern**

### **Controller ke Inertia**
```php
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Users/Index', [
            // Eager data - loaded immediately
            'users' => User::query()
                ->when($request->search, fn($q, $search) => 
                    $q->where('name', 'like', "%{$search}%")
                )
                ->select('id', 'name', 'email', 'status', 'created_at')
                ->with('roles:id,name') // Only needed columns
                ->paginate(20)
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,
                    'roles' => $user->roles->pluck('name'),
                    'created_at' => $user->created_at->diffForHumans(),
                ]),
            
            // Filters - always included
            'filters' => $request->only(['search', 'status', 'role']),
            
            // Lazy data - loaded only when requested
            'stats' => Inertia::lazy(fn() => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
            ]),
            
            // Defer - loaded after initial page render
            'recentActivity' => Inertia::defer(fn() => 
                Activity::with('user:id,name')
                    ->latest()
                    ->limit(10)
                    ->get()
            ),
        ]);
    }
    
    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile' => $user->profile,
                'created_at' => $user->created_at->format('d M Y'),
            ],
            
            // Lazy load heavy data
            'orders' => Inertia::lazy(fn() => 
                $user->orders()
                    ->with('items')
                    ->latest()
                    ->paginate(10)
            ),
        ]);
    }
}
```
### **Shared Data (HandleInertiaRequests)**
```php
// app/Http/Middleware/HandleInertiaRequests.php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        // Auth user - always available
        'auth' => fn() => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'avatar' => $request->user()->avatar_url,
                'roles' => $request->user()->roles->pluck('name'),
                'permissions' => $request->user()->getAllPermissions()->pluck('name'),
            ] : null,
        ],
        
        // Flash messages
        'flash' => fn() => [
            'success' => $request->session()->get('success'),
            'error' => $request->session()->get('error'),
            'warning' => $request->session()->get('warning'),
            'info' => $request->session()->get('info'),
        ],
        
        // App configuration
        'appName' => config('app.name'),
        'appUrl' => config('app.url'),
        
        // CSRF token
        'csrf_token' => csrf_token(),
        
        // Ziggy routes (if using Ziggy)
        'ziggy' => fn() => [
            ...(new Ziggy)->toArray(),
            'location' => $request->url(),
        ],
    ]);
}
```

---

## 🖼️ **6. Vue 3 Best Practices**

### **Composition API with Script Setup**
```vue
<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { useNotification } from '@/Composables/useNotification'

// Props with TypeScript-style definition
const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  errors: Object
})

// Emits
const emit = defineEmits(['update', 'delete'])

// Composables
const { notify } = useNotification()

// Reactive state
const isLoading = ref(false)
const showModal = ref(false)

// Form handling with Inertia
const form = useForm({
  name: props.user?.name || '',
  email: props.user?.email || '',
  avatar: null
})

// Computed properties
const hasChanges = computed(() => {
  return form.isDirty
})

const fullName = computed(() => {
  return `${props.user.first_name} ${props.user.last_name}`
})

// Methods
const submitForm = () => {
  form.post(route('users.store'), {
    preserveScroll: true,
    onSuccess: () => {
      notify('User created successfully', 'success')
      emit('update')
    },
    onError: (errors) => {
      notify('Please check the form', 'error')
    },
    onFinish: () => {
      isLoading.value = false
    }
  })
}

const deleteUser = () => {
  if (confirm('Are you sure?')) {
    router.delete(route('users.destroy', props.user.id), {
      onSuccess: () => {
        notify('User deleted', 'success')
        emit('delete')
      }
    })
  }
}

// Watchers
watch(() => props.user, (newUser) => {
  form.name = newUser.name
  form.email = newUser.email
}, { deep: true })

// Lifecycle hooks
onMounted(() => {
  console.log('Component mounted')
})
</script>

<template>
  <div class="max-w-2xl mx-auto">
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Form fields -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">
          Name
        </label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          :class="{ 'border-red-500': form.errors.name }"
        />
        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
          {{ form.errors.name }}
        </p>
      </div>
      
      <!-- Submit button -->
      <button
        type="submit"
        :disabled="form.processing || !hasChanges"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
      >
        {{ form.processing ? 'Saving...' : 'Save Changes' }}
      </button>
    </form>
  </div>
</template>
```

### **Composables (reusable logic)**
```javascript
// Composables/useNotification.js
import { ref } from 'vue'

const notifications = ref([])

export function useNotification() {
  const notify = (message, type = 'info', duration = 3000) => {
    const id = Date.now()
    
    notifications.value.push({
      id,
      message,
      type,
    })
    
    setTimeout(() => {
      remove(id)
    }, duration)
  }
  
  const remove = (id) => {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index > -1) {
      notifications.value.splice(index, 1)
    }
  }
  
  return {
    notifications,
    notify,
    remove
  }
}

// Composables/useModal.js
import { ref } from 'vue'

export function useModal() {
  const isOpen = ref(false)
  
  const open = () => {
    isOpen.value = true
  }
  
  const close = () => {
    isOpen.value = false
  }
  
  const toggle = () => {
    isOpen.value = !isOpen.value
  }
  
  return {
    isOpen,
    open,
    close,
    toggle
  }
}

// Composables/usePagination.js
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

export function usePagination(data) {
  const currentPage = ref(data.current_page)
  
  const hasMore = computed(() => {
    return currentPage.value < data.last_page
  })
  
  const goToPage = (page) => {
    router.get(data.path, 
      { page },
      { 
        preserveState: true,
        preserveScroll: true,
        only: ['data']
      }
    )
  }
  
  return {
    currentPage,
    hasMore,
    goToPage
  }
}
```

### **Component Organization**
```vue
<script setup>
// 1. Imports
import { ref, computed } from 'vue'
import UserCard from '@/Components/UserCard.vue'

// 2. Props & Emits
const props = defineProps({...})
const emit = defineEmits(['update', 'delete'])

// 3. Composables
const { isOpen, toggle } = useModal()

// 4. Reactive state
const count = ref(0)

// 5. Computed
const doubleCount = computed(() => count.value * 2)

// 6. Methods
const increment = () => count.value++

// 7. Lifecycle hooks
onMounted(() => {})
</script>

<template>
  <!-- Template here -->
</template>
```

---

## 🎨 **7. Tailwind CSS 4 Guidelines**

### **Class Organization**
```vue
<!-- 
Order: 
1. Layout (display, position, flex, grid)
2. Spacing (margin, padding)
3. Sizing (width, height)
4. Typography (font, text)
5. Visual (color, background, border, shadow)
6. Effects (opacity, transform, transition, animation)
7. Interactivity (cursor, pointer-events)
8. Responsive modifiers
9. State modifiers (hover, focus, active)
-->

<div class="
  flex items-center justify-between
  px-4 py-3
  w-full max-w-4xl
  text-sm font-medium
  bg-white border border-gray-200 rounded-lg shadow-sm
  transition-all duration-200
  hover:shadow-md hover:border-gray-300
  focus:outline-none focus:ring-2 focus:ring-blue-500
  sm:px-6 md:px-8
">
  Content
</div>

<!-- ❌ BAD - Random order -->
<div class="shadow-sm px-4 hover:shadow-md flex bg-white rounded-lg items-center py-3 justify-between transition-all">
  Content
</div>
```

### **Reusable Component with Dynamic classes**
```vue
<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'danger', 'success'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  disabled: Boolean
})

const buttonClasses = computed(() => {
  const base = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed'
  
  const variants = {
    primary: 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    secondary: 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-500',
    danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    success: 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500'
  }
  
  const sizes = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-base',
    lg: 'px-6 py-3 text-lg'
  }
  
  return [
    base,
    variants[props.variant],
    sizes[props.size]
  ].join(' ')
})
</script>

<template>
  <button :class="buttonClasses" :disabled="disabled">
    <slot />
  </button>
</template>
```

### **Responsive Design**
```vue
<template>
  <!-- Mobile-first approach -->
  <div class="
    grid grid-cols-1 gap-4
    sm:grid-cols-2 sm:gap-6
    md:grid-cols-3
    lg:grid-cols-4 lg:gap-8
    xl:grid-cols-5
  ">
    <!-- Cards -->
  </div>
  
  <!-- Container with responsive padding -->
  <div class="
    container mx-auto
    px-4
    sm:px-6
    lg:px-8
  ">
    <!-- Content -->
  </div>
  
  <!-- Responsive text -->
  <h1 class="
    text-2xl font-bold
    sm:text-3xl
    md:text-4xl
    lg:text-5xl
  ">
    Heading
  </h1>
</template>
```

### **Dark mode support**
```vue
<template>
  <div class="
    bg-white text-gray-900
    dark:bg-gray-900 dark:text-white
  ">
    <p class="
      text-gray-600
      dark:text-gray-400
    ">
      Description
    </p>
    
    <button class="
      bg-blue-600 hover:bg-blue-700
      dark:bg-blue-500 dark:hover:bg-blue-600
    ">
      Action
    </button>
  </div>
</template>
```
---

## **8. Error Handling**

### **Global Exception Handler**
```php
// app/Exceptions/Handler.php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Inertia\Inertia;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        ValidationException::class,
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Send to error tracking service (Sentry, Bugsnag, etc.)
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry') && $this->shouldReport($e)) {
                app('sentry')->captureException($e);
            }
        });

        // Custom Inertia error responses
        $this->renderable(function (Throwable $e, $request) {
            if ($request->hasHeader('X-Inertia')) {
                $response = $this->prepareJsonResponse($request, $e);
                $statusCode = $response->getStatusCode();
                
                // Redirect to custom error pages
                if (in_array($statusCode, [500, 503, 404, 403])) {
                    return Inertia::render('Errors/Error', [
                        'status' => $statusCode,
                        'message' => $this->getErrorMessage($e, $statusCode),
                    ])->toResponse($request)->setStatusCode($statusCode);
                }
            }
        });
    }
    
    protected function getErrorMessage(Throwable $e, int $statusCode): string
    {
        if (app()->environment('production')) {
            return match($statusCode) {
                404 => 'Page not found',
                403 => 'Forbidden',
                500 => 'Server error',
                503 => 'Service unavailable',
                default => 'An error occurred'
            };
        }
        
        return $e->getMessage();
    }
}
```

### **Custom Exceptions**
```php
// app/Exceptions/PaymentFailedException.php
namespace App\Exceptions;

use Exception;

class PaymentFailedException extends Exception
{
    public function __construct(
        string $message = 'Payment processing failed',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
    
    public function render($request)
    {
        return back()
            ->withInput()
            ->with('error', $this->message);
    }
    
    public function report()
    {
        // Log to specific channel
        Log::channel('payments')->error($this->message, [
            'exception' => get_class($this),
            'trace' => $this->getTraceAsString()
        ]);
    }
}

// Usage
throw new PaymentFailedException('Insufficient funds');
```

### **Try-Catch Best practices**
```php
// Service layer
public function processOrder(Order $order): void
{
    try {
        DB::transaction(function () use ($order) {
            // Multiple operations
            $this->chargePayment($order);
            $this->updateInventory($order);
            $this->sendNotification($order);
        });
    } catch (PaymentFailedException $e) {
        // Handle payment-specific errors
        Log::error('Payment failed for order: ' . $order->id, [
            'error' => $e->getMessage()
        ]);
        throw $e;
    } catch (InsufficientStockException $e) {
        // Handle inventory errors
        $this->notifyAdmin($order, $e);
        throw $e;
    } catch (\Exception $e) {
        // Catch-all for unexpected errors
        Log::critical('Unexpected error processing order: ' . $order->id, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw new OrderProcessingException('Failed to process order', 0, $e);
    }
}
```

### **Vue Error Handling**
```javascript
// app.js
import { createInertiaApp } from '@inertiajs/vue3'
import { createApp } from 'vue'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
    
    // Global error handler
    app.config.errorHandler = (err, instance, info) => {
      console.error('Global error:', err)
      
      // Send to error tracking
      if (window.Sentry) {
        window.Sentry.captureException(err)
      }
      
      // Show user-friendly message
      // Use your notification system
    }
    
    app.mount(el)
  },
})
```

## 📝 **9. Code Quality Rules**

### **Comments & Documentation**
```php
// ✅ GOOD - Explain WHY, not WHAT
// Calculate discount based on user tier because premium users get 20% off
$discount = $user->isPremium() ? 0.20 : 0;

// ❌ BAD - Obvious comment
// Set discount to 0.20
$discount = 0.20;

/**
 * Calculate total price with applicable discounts
 * 
 * @param float $basePrice Original price before discounts
 * @param User $user User to check for tier-based discounts
 * @return float Final price after discounts
 */
public function calculatePrice(float $basePrice, User $user): float
{
    // Implementation
}
```

### **Error Handling**
```php
// ✅ GOOD
try {
    $user = $this->userService->createUser($data);
    return response()->json($user, 201);
} catch (ValidationException $e) {
    return response()->json(['error' => $e->getMessage()], 422);
} catch (\Exception $e) {
    Log::error('User creation failed', ['error' => $e->getMessage()]);
    return response()->json(['error' => 'Something went wrong'], 500);
}
```

### **Magic Numbers**
```php
// ❌ BAD
if ($user->age > 17) { ... }

// ✅ GOOD
const MINIMUM_AGE = 18;
if ($user->age >= self::MINIMUM_AGE) { ... }

// ✅ BETTER - Use Enum (Laravel 12)
enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
}

if ($user->status === UserStatus::ACTIVE) { ... }
```

---

## 🧪 **10. Testing Standards**

### **Feature Test**
```php
// tests/Feature/UserManagementTest.php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_be_created_with_valid_data(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        $response = $this->post(route('users.store'), $data);
        
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
        
        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue($user->hasRole('user'));
    }
    
    public function test_user_creation_fails_with_invalid_email(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        $response = $this->post(route('users.store'), $data);
        
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 0);
    }
    
    public function test_authenticated_user_can_update_own_profile(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->put(route('users.update', $user), [
                'name' => 'Updated Name',
                'email' => $user->email,
            ]);
        
        $response->assertRedirect();
        $this->assertEquals('Updated Name', $user->fresh()->name);
    }
    
    public function test_user_cannot_update_another_users_profile(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->put(route('users.update', $otherUser), [
                'name' => 'Hacked Name',
            ]);
        
        $response->assertForbidden();
    }
}
```

### **Unit Test**
```php
// tests/Unit/UserServiceTest.php
namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_create_user_assigns_default_role(): void
    {
        $service = new UserService();
        
        $user = $service->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        
        $this->assertTrue($user->hasRole('user'));
    }
    
    public function test_create_user_hashes_password(): void
    {
        $service = new UserService();
        
        $user = $service->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
```
### **Database factories**
```php
// database/factories/UserFactory.php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ];
    }
    
    // State modifiers
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    public function admin(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('admin');
        });
    }
    
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

// Usage in tests
$user = User::factory()->create();
$admin = User::factory()->admin()->create();
$inactiveUser = User::factory()->inactive()->create();
$users = User::factory()->count(10)->create();
```

### **Pest PHP (Modern Testing)**
```php
// tests/Feature/UserTest.php
use App\Models\User;

it('creates a user successfully', function () {
    $response = $this->post(route('users.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    
    $response->assertRedirect(route('users.index'));
    expect(User::where('email', 'john@example.com'))->toExist();
});

it('requires authentication to create user', function () {
    $response = $this->post(route('users.store'), []);
    $response->assertRedirect(route('login'));
});

it('validates email format', function () {
    $response = $this->post(route('users.store'), [
        'email' => 'invalid-email',
    ]);
    
    $response->assertSessionHasErrors('email');
});

// Using datasets
it('validates required fields', function ($field) {
    $response = $this->post(route('users.store'), [
        $field => '',
    ]);
    
    $response->assertSessionHasErrors($field);
})->with(['name', 'email', 'password']);
```
---

## 🔒 **11. Security Rules**

### **Input Validation & Sanitization**
```php
// Always use Form Requests
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],
            'bio' => ['nullable', 'string', 'max:500'],
        ];
    }
    
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->email)),
            'name' => strip_tags($this->name),
            'bio' => strip_tags($this->bio),
        ]);
    }
}

```
### **SQL Injection Prevention**

```php
// ✅ GOOD - Eloquent (auto-escapes)
User::where('email', $email)->first();
User::whereIn('status', $statuses)->get();

// ✅ GOOD - Query Builder with bindings
DB::table('users')
    ->where('email', $email)
    ->first();

// ✅ GOOD - Raw with bindings
DB::select('SELECT * FROM users WHERE email = ?', [$email]);
DB::select('SELECT * FROM users WHERE email = :email', ['email' => $email]);

// ❌ NEVER DO THIS
DB::select("SELECT * FROM users WHERE email = '{$email}'");
User::whereRaw("email = '{$email}'")->first();
```

### **Mass Assignment Protection**
```php
// Model
class User extends Model
{
    // Whitelist approach (recommended)
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    
    // OR Blacklist approach
    protected $guarded = [
        'id',
        'is_admin',
        'created_at',
        'updated_at',
    ];
}

// Controller
// ✅ GOOD - Only validated data
$user = User::create($request->validated());

// ❌ BAD - All request data
$user = User::create($request->all());
```

### **Authentication && Authorization**
```php
// Policies for authorization
// app/Policies/UserPolicy.php
class UserPolicy
{
    public function update(User $currentUser, User $user): bool
    {
        return $currentUser->id === $user->id || $currentUser->isAdmin();
    }
    
    public function delete(User $currentUser, User $user): bool
    {
        return $currentUser->isAdmin() && $currentUser->id !== $user->id;
    }
}

// Controller
public function update(UpdateUserRequest $request, User $user)
{
    $this->authorize('update', $user);
    
    $user->update($request->validated());
    
    return redirect()->route('users.show', $user);
}

// OR using Gate
Gate::authorize('update', $user);

// Blade/Vue
@can('update', $user)
    <!-- Show edit button -->
@endcan
```

### **CSRF Protection**
```php
// Automatically enabled in Laravel
// forms automatically include CSRF token with Inertia

// For custom AJAX requests
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(data)
})
```

### **File upload security**
```php
// Validation
$request->validate([
    'avatar' => [
        'required',
        'image',
        'max:2048', // 2MB
        'mimes:jpg,jpeg,png',
        'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
    ],
]);

// Secure storage
$path = $request->file('avatar')->store('avatars', 'public');

// Generate safe filename
$filename = Str::uuid() . '.' . $request->file('avatar')->extension();
$path = $request->file('avatar')->storeAs('avatars', $filename, 'public');

// Delete old file when updating
if ($user->avatar) {
    Storage::disk('public')->delete($user->avatar);
}
$user->avatar = $path;
$user->save();
```

### **Xss prevention**
```php
// Blade auto-escapes
{{ $user->name }} // Safe

{!! $user->bio !!} // Dangerous! Only use with trusted content

// Vue (Inertia) auto-escapes
<div>{{ user.name }}</div> // Safe

// Dangerous - only use with sanitized content
<div v-html="sanitizedContent"></div>

// Use DOMPurify for user-generated HTML
import DOMPurify from 'dompurify'

const sanitizedContent = computed(() => {
  return DOMPurify.sanitize(props.content)
})
```

### **Rate Limiting**
```php
// Route rate limiting
Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Per-user rate limiting
Route::middleware(['auth', 'throttle:100,1,user'])->group(function () {
    Route::post('/api/posts', [PostController::class, 'store']);
});

// Custom rate limiting
// app/Providers/RouteServiceProvider.php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Named rate limiters
RateLimiter::for('uploads', function (Request $request) {
    return $request->user()->isPremium()
        ? Limit::none()
        : Limit::perHour(10)->by($request->user()->id);
});
```

### **Environment Security**
```php
// .env - NEVER commit to git
APP_KEY=base64:generated_key_here
DB_PASSWORD=strong_password_here

// .env.example - Safe to commit
APP_KEY=
DB_PASSWORD=

// Ensure .env is in .gitignore
// .gitignore
.env
.env.backup
.env.production

// Access environment variables
config('app.key') // ✅ GOOD
env('APP_KEY') // ❌ BAD - only use in config files

// config/app.php
return [
    'key' => env('APP_KEY'),
    'name' => env('APP_NAME', 'Laravel'),
];
```

---

## 📦 **11. Git Commit Convention**

```bash
# Format: <type>(<scope>): <subject>

feat(user): add user registration feature
fix(auth): resolve login redirect issue
refactor(product): optimize query performance
docs(readme): update installation guide
style(header): adjust navbar spacing
test(user): add user creation test
chore(deps): update laravel to v12
```

**Types:**
- `feat` - New feature
- `fix` - Bug fix
- `refactor` - Code refactoring
- `docs` - Documentation
- `style` - Formatting (tidak mengubah logic)
- `test` - Testing
- `chore` - Maintenance

---

## ✨ **12. Code Review Checklist**

- [ ] Kode mengikuti naming convention
- [ ] Tidak ada N+1 query problem
- [ ] Form validation menggunakan Form Request
- [ ] Error handling sudah proper
- [ ] Tidak ada hardcoded values
- [ ] Menggunakan Eloquent relationship dengan benar
- [ ] Component Vue reusable dan maintainable
- [ ] Tailwind classes terorganisir dengan baik
- [ ] Ada unit/feature test untuk fitur baru
- [ ] Dokumentasi/comment untuk logic kompleks
- [ ] Migration dapat di-rollback
- [ ] Sensitive data tidak ter-commit (.env)
---

## 🚀 **13. Performance Tips**

```php
// ✅ Eager loading
$users = User::with('orders')->get();

// ✅ Pagination
$users = User::paginate(20);

// ✅ Caching
Cache::remember('users', 3600, fn() => User::all());

// ✅ Index database columns yang sering di-query
Schema::table('users', function (Blueprint $table) {
    $table->index('email');
});

// ✅ Lazy load components (Vue)
const UserProfile = defineAsyncComponent(() => 
  import('./Components/UserProfile.vue')
)
```

---

## 📌 **Summary: Golden Rules**

1. **DRY** - Don't Repeat Yourself
2. **KISS** - Keep It Simple, Stupid
3. **SOLID** Principles
4. **Thin Controllers, Fat Services**
5. **Always validate input**
6. **Optimize database queries**
7. **Write tests for critical features**
8. **Use TypeScript untuk type safety (opsional tapi recommended)**
9. **Component harus reusable**
10. **Code readability > Code cleverness**

---

**Last Updated:** January 2026  
**Version:** 1.0