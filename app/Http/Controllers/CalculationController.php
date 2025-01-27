<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calculation;
use Inertia\Inertia;
use App\Http\Requests\CalculationRequest;

class CalculationController

{

    public function index()
    {
        $previous_calculations = \Auth::user()->calculations()
            ->latest()
            ->take(10)
            ->get();
        return Inertia::render('Calculator/Calculator', [
            'previous_calculations' => $previous_calculations
        ]);
    }
    public function calculate(CalculationRequest $request)
    {

        $numbers = $request->numbers;
        $operators = $request->operators;

        if (count($numbers) - 1 !== count($operators)) {
            return response()->json([
                'error' => 'Invalid input: operators count must match numbers count - 1.'
            ], 400);
        }

        $result = array_shift($numbers);
        foreach ($operators as $index => $operator) {
            $nextNumber = $numbers[$index];
            $result = match ($operator) {
                '+' => $result + $nextNumber,
                '-' => $result - $nextNumber,
                '*' => $result * $nextNumber,
                '/' => $nextNumber != 0 ? $result / $nextNumber : 'N/A',
                default => throw new \InvalidArgumentException('Invalid Operator'),
            };
        }

        \Auth::user()->calculations()->create([
            // 'user_id' => auth()->id(),

            'operation' => implode(' ', $operators),
            'numbers' => $request->numbers,
            'result' => $result,
        ]);
        $previous_calculations = \Auth::user()->calculations()
            ->latest()
            ->take(10)
            ->get();
        $processData = [
            'result' => $result,
            'previous_calculations' => $previous_calculations
        ];

        return redirect()->back()->with(["processData" => $processData]);
    }
}
