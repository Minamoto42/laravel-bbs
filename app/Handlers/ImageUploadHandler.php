<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Image upload handler
 */
class ImageUploadHandler
{
    /**
     * @var array|string[] Allowed file extensions
     */
    protected array $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    /**
     * Upload image
     *
     * @param $file
     * @param $folder
     * @param $file_prefix
     * @param string $folder
     * @param string $file_prefix
     * @param int|bool $max_width
     * @return array|bool
     */
    public function save($file, string $folder, string $file_prefix, int|bool $max_width = false): array|bool
    {
        // Build the storage folder
        // ex: uploads/images/avatars/201709/21/
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // The path of the file storage folder
        // ex: /var/www/laravel-bbs-202501/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // Get the file extension from the file
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // The file name is composed of the prefix, the date of the upload, and the file extension
        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        // If the uploaded file is not an image, terminate the operation
        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // Move the file to the specified folder
        $file->move($upload_path, $filename);

        // If the specified width is provided and the image is not an animated GIF, reduce the size
        if ($max_width && $extension != 'gif') {
            $this->reducesSize($upload_path . '/' . $filename, $max_width);
        }



        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    /**
     * Reduce the size of the image
     *
     * @param string $file_path
     * @param int $max_width
     */
    public function reducesSize(string $file_path, int $max_width): void
    {
        Image::read($file_path) // 读取图片, $file_path 为图片的物理路径, 例如: /var/www/laravel-bbs-202501/public/uploads/images/avatars/201709/21/1505982180_7p0gsdJz9v.png
        ->scale(width: $max_width) // 限制最大宽度为 $max_width，等比例缩放高度
        ->save(); // 保存
    }
}
