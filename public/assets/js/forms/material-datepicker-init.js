// MAterial Date picker
$("#mdate").bootstrapMaterialDatePicker({ weekStart: 0, time: false });
$("#timepicker").bootstrapMaterialDatePicker({
  format: "HH:mm",
  time: true,
  date: false,
});
$("#date-format").bootstrapMaterialDatePicker({
  format: "dddd DD MMMM YYYY - HH:mm",
});

$("#min-date").bootstrapMaterialDatePicker({
  format: "DD/MM/YYYY HH:mm",
  minDate: new Date(),
});
$("#date-fr").bootstrapMaterialDatePicker({
  format: "DD/MM/YYYY HH:mm",
  lang: "fr",
  weekStart: 1,
  cancelText: "ANNULER",
});
$("#date-end").bootstrapMaterialDatePicker({ weekStart: 0 });
$("#date-start")
  .bootstrapMaterialDatePicker({ weekStart: 0 })
  .on("change", function (e, date) {
    $("#date-end").bootstrapMaterialDatePicker("setMinDate", date);
  });

// Custom Show / Hide Configurations
// Custom Show / Hide Configurations
document.querySelectorAll('.repeater-group').forEach(function (el) {
  $(el).repeater({
    show: function () {
      // Saat item baru ditambahkan
      $(this).slideDown();

      // Cari dan reset elemen yang menampilkan old attachment
      $(this).find('[data-conditional-attachment]').each(function () {
        $(this).html('File attachment');
      });

      // Jika kamu juga perlu reset input file
      $(this).find('input[type="file"]').val('');
    },
    hide: function (remove) {
      if (confirm("Are you sure you want to remove this item?")) {
        $(this).slideUp(remove);
      }
    }
  });
});


document.querySelectorAll(".editor_quill").forEach(editorDiv => {
  const inputName = editorDiv.getAttribute("input_name");
  const initialValue = editorDiv.getAttribute("data-value") || "";

  // Buat Quill editor
  const quill = new Quill(editorDiv, {
    theme: "snow",
    placeholder: "Write something..."
  });

  // Isi awal jika ada
  quill.root.innerHTML = initialValue;

  // Simpan instance ke element
  editorDiv._quill = quill;

  // Buat input hidden otomatis & append ke parent
  const hiddenInput = document.createElement("input");
  hiddenInput.type = "hidden";
  hiddenInput.name = inputName;
  hiddenInput.value = initialValue;
  editorDiv.parentNode.appendChild(hiddenInput);

  // Simpan input juga ke instance
  editorDiv._hiddenInput = hiddenInput;

  // Update hidden input setiap kali konten berubah
  quill.on("text-change", function () {
    hiddenInput.value = quill.root.innerHTML;
  });
});

// Saat form disubmit, isi hidden input dengan isi Quill (backup)
document.querySelectorAll("form").forEach(form => {
  form.addEventListener("submit", function () {
    const editors = form.querySelectorAll(".editor_quill");
    editors.forEach(editorDiv => {
      const quill = editorDiv._quill;
      const hiddenInput = editorDiv._hiddenInput;
      if (quill && hiddenInput) {
        hiddenInput.value = quill.root.innerHTML;
      }
    });
  });
});
