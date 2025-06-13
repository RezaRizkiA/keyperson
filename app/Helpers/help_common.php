<?php

use Illuminate\Support\Str;

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
