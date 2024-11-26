<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveRequest;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    public function index(Room $room): View
    {
        return view('room.index', ['room' => $room]);
    }

    public function join(Room $room): RedirectResponse
    {
        session()->remove('side');

        if ($this->roomService->isBoardEmpty($room->board)) {
            session()->put('side', $this->roomService->changeMove($room->host_move));
        } else {
            session()->put('side', $room->current_move);
        }


        return redirect()->route('rooms.index', ['room' => $room]);
    }

    public function store(Request $request): RedirectResponse
    {
        $side = $request->input('side');
        $room = $this->roomService->createBoard($side);

        return redirect()->route('rooms.index', ['room' => $room->id]);
    }

    public function move(Room $room, MoveRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!$this->roomService->updateBoard($room, session('side'), $data['x'], $data['y'])) {
            return response()->json([
                'success' => false,
                'message' => 'It is not your turn!',
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function refresh(Room $room): JsonResponse
    {
        $this->roomService->refreshBoard($room);

        return response()->json([
            'success' => true,
        ]);
    }
}
