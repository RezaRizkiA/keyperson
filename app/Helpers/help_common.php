<?php

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\User;

function slugName($name, $id = null)
{
    $slug = Str::of($name)->slug('-');
    return $id ? "{$slug}-{$id}" : (string) $slug;
} //membuat slug

function fileName($file, $extension = null)
{
    $originalName             = $file->getClientOriginalName();
    $originalExtension        = $extension ?? pathinfo($originalName, PATHINFO_EXTENSION);
    $fileNameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
    $fileNameWithoutTimestamp = preg_replace('/_\d+$/', '', $fileNameWithoutExtension);
    $slug                     = Str::slug($fileNameWithoutTimestamp);
    $timestamp                = date('YmdHis');
    return "{$slug}-{$timestamp}.{$originalExtension}";
}

function urlpathSTORAGE($path)
{
    if (checkdnsrr('google.com') && $path != null) {
        $bucket   = config('filesystems.disks.s3.bucket');
        $endpoint = rtrim(config('filesystems.disks.s3.endpoint'), '/');
        return "{$endpoint}/{$bucket}/" . ltrim($path, '/');
    }
}

if (!function_exists('getSocialMedias')) {
    function getSocialMedias(?User $user): array
    {
        if (!$user || !in_array('expert', $user->roles ?? [])) {
            return [];
        }

        $rawSocials = $user->expert->socials ?? [];
        if (!is_array($rawSocials)) return [];

        $config = [
            'facebook' => [
                'class' => 'btn-primary',
                'icon' => 'ti ti-brand-facebook',
            ],
            'instagram' => [
                'class' => 'btn-secondary',
                'icon' => 'ti ti-brand-instagram',
            ],
            'youtube' => [
                'class' => 'btn-danger',
                'icon' => 'ti ti-brand-youtube',
            ],
            'linkedin' => [
                'class' => 'btn-info',
                'icon' => 'ti ti-brand-linkedin',
            ],
        ];

        $result = [];

        foreach ($rawSocials as $sosmed) {
            $key = trim(strtolower($sosmed['key'] ?? ''));
            $val = trim($sosmed['value'] ?? '');

            if (!$key || !$val || !filter_var($val, FILTER_VALIDATE_URL)) {
                continue;
            }

            $result[] = [
                'key' => $key,
                'url' => $val,
                'btn_class' => $config[$key]['class'] ?? 'btn-dark',
                'icon_class' => $config[$key]['icon'] ?? 'ti ti-link',
            ];
        }

        return $result;
    }
}
