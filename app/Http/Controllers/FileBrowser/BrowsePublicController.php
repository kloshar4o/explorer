<?php

namespace App\Http\Controllers\FileBrowser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\FileManagerFolder;
use App\FileManagerFile;
use App\Share;

class BrowsePublicController extends Controller
{

    /**
     * Get all public shared items
     *
     * @return Collection
     */
    public function shared()
    {
        // Get shared folders and files
        $folder_ids = Share::query()
            ->where('type', 'folder')
            ->pluck('item_id');

        $file_ids = Share::query()
            ->where('type', '!=', 'folder')
            ->pluck('item_id');

        // Get folders and files
        $folders = FileManagerFolder::with(['parent', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->whereIn('unique_id', $folder_ids)
            ->limit(10)
            ->get();

        $files = FileManagerFile::with(['parent', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->whereIn('unique_id', $file_ids)
            ->limit(10)
            ->get();


        $items = collect([$folders, $files])
            ->collapse()
            ->sortBy('created_at')
            ->splice(0, 10);


        // Collect folders and files to single array
        return $items;
    }

}
