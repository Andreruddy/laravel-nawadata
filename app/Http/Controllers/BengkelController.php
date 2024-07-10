<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BengkelController extends Controller
{
    public function index()
    {
        $workshops = json_decode(Storage::get('json/workshop.json'), true);
        $bookings = json_decode(Storage::get('json/booking.json'), true);

        if (is_null($workshops) || is_null($bookings)) {
            return response()->json([
                "status" => 0,
                "message" => "Failed to retrieve data.",
                "data" => []
            ]);
        }
        $mergedData = array_map(function ($booking) use ($workshops) {
            $workshopDetails = collect($workshops)->firstWhere('code', $booking['booking']['workshop']['code']);
            if (is_null($workshopDetails)) {
                return null;
            }
            return [
                "name" => $booking["name"],
                "email" => $booking["email"],
                "booking_number" => $booking["booking"]["booking_number"],
                "book_date" => $booking["booking"]["book_date"],
                "ahass_code" => $workshopDetails["code"],
                "ahass_name" => $workshopDetails["name"],
                "ahass_address" => $workshopDetails["address"],
                "ahass_contact" => $workshopDetails["phone_number"],
                "ahass_distance" => $workshopDetails["distance"],
                "motorcycle_ut_code" => $booking["booking"]["motorcycle"]["ut_code"],
                "motorcycle" => $booking["booking"]["motorcycle"]["name"]
            ];
        }, $bookings);
        $mergedData = array_filter($mergedData);
        $sortedData = collect($mergedData)->sortBy(['ahass_distance', 'asc'])->values()->all();
        return response()->json([
            "status" => 1,
            "message" => "Data Successfully Retrieved.",
            "data" => $sortedData
        ]);
    }
}
