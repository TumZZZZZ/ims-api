<?php

namespace App\Services;

class SVUpload
{
    public function upload(array $params)
    {
        if (isset($params['image']) && $params['image']) {
            $image    = $params['image'];
            $folder   = 'storage/images/' . strtolower($params['type']);
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($folder), $filename);
            return $folder . '/' . $filename;
        }

        return null;
    }
}
