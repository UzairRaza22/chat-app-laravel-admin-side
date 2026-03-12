<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Message;
use App\Http\Resources\admin\AdminMessageResource;

class AdminMessageController extends Controller
{
    public function read(Request $request)
    {
        $query = Message::with(['channel', 'sender']);

        Message::addFilters($request, $query);

        $perPage = data_get($request, 'per_page', 10);
        $messages = $query->paginate($perPage);

        return response()->success([
            'messages' => AdminMessageResource::collection($messages),
            'pagination' => [
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
            ]
        ]);
    }
}
