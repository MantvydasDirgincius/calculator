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


        $tempNumbers = [$numbers[0]];
        $tempOperators = [];
        for ($i = 0; $i < count($operators); $i++) {
            if ($operators[$i] === '*' || $operators[$i] === '/') {
                $prevNumber = array_pop($tempNumbers);
                $currentNumber = $numbers[$i + 1];
                $result = $operators[$i] === '*'
                    ? $prevNumber * $currentNumber
                    : ($currentNumber != 0 ? $prevNumber / $currentNumber : 'N/A');

                if ($result === 'N/A') {
                    return response()->json([
                        'error' => 'Division by zero detected.'
                    ], 400);
                }
                $tempNumbers[] = $result;
            } else {
                $tempNumbers[] = $numbers[$i + 1];
                $tempOperators[] = $operators[$i];
            }
        }


        $result = array_shift($tempNumbers);
        foreach ($tempOperators as $index => $operator) {
            $nextNumber = $tempNumbers[$index];
            $result = $operator === '+'
                ? $result + $nextNumber
                : $result - $nextNumber;
        }


        \Auth::user()->calculations()->create([
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
            'previous_calculations' => $previous_calculations,
        ];

        return redirect()->back()->with(["processData" => $processData]);
    }
}
