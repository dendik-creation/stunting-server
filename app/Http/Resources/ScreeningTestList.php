<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScreeningTestList extends JsonResource
{
     /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->groupAndSortData($this->resource);
    }

    /**
     * Private function to group and sort the data by step.
     *
     * @param \Illuminate\Support\Collection $collection
     * @return array
     */
    private function groupAndSortData($collection)
    {
        $grouped = [];

        foreach ($collection as $item) {
            $step = $item['step'];

            // Group by step
            if (!isset($grouped[$step])) {
                $grouped[$step] = [
                    'step' => $step,
                    'tanggal' => $item['tanggal'],
                    'tingkat_kemandirian' => null,
                    'kesehatan_lingkungan' => null,
                ];
            }

            // Append tingkat_kemandirian
            if (isset($item['tingkat_kemandirian'])) {
                $grouped[$step]['tingkat_kemandirian'] = $item['tingkat_kemandirian'];
            }

            // Append kesehatan_lingkungan
            if (isset($item['kesehatan_lingkungan'])) {
                $grouped[$step]['kesehatan_lingkungan'] = $item['kesehatan_lingkungan'];
            }
        }

        // Descending Step
        usort($grouped, function ($a, $b) {
            return $b['step'] <=> $a['step'];
        });

        return $grouped;
    }
}
