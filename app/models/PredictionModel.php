<?php


class PredictionModel extends Model
{
    public function Prediction(array $goroscop, int $year, int $month, int $zodiac): void
    {
        foreach ($goroscop['gor'] as $key => $item) {
            $prediction_id = (int)$this->fetch_assoc("SELECT `id` FROM `type_prediction` WHERE `value`='m-$key'")['id'];
            if ($prediction_id > 0) {
                if ($prediction_id === 5) {
                    foreach ($item as $key2 => $item2) {
                        $this->insertPrediction($year, $month, $zodiac, $prediction_id, (string)$item2, $key2);
                    }
                } else {
                    $this->insertPrediction($year, $month, $zodiac, $prediction_id, (string)$item);
                }
            }
        }
        if (isset($goroscop['gyear'])) {
            foreach ($goroscop['gyear'] as $key => $item) {
                $prediction_id = (int)$this->fetch_assoc("SELECT `id` FROM `type_prediction` WHERE `value`='y-$key'")['id'];
                if ($prediction_id > 0) {
                    $this->insertPrediction($year, 0, $zodiac, $prediction_id, (string)$item);
                }
            }
        }
    }

    private function insertPrediction(int $year, int $month, int $zodiac, int $prediction_id, string $prediction, $day = 0): void
    {
        $result = $this->fetch_assoc("SELECT * FROM `prediction_to_zodiac` 
                    WHERE 
                          `year`=$year AND 
                          `month`=$month AND 
                          `zodiac_id`=$zodiac AND 
                          `day`=$day AND 
                          `prediction_id`=$prediction_id");
        if (!$result) {
            $this->query(
                "INSERT INTO `prediction_to_zodiac` SET 
                                       `zodiac_id`=$zodiac, 
                                       `prediction_id`=$prediction_id, 
                                       `year`=$year, 
                                       `month`=$month, 
                                       `day`=$day,
                                       `prediction`='$prediction'");
        }
    }

    public function clearParse(): void
    {
        $this->query('TRUNCATE `prediction_to_zodiac`');
    }
}