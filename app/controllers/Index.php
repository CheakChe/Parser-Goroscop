<?php


class Index extends AbstractController
{
    private $current_year;
    private $prediction_model;

    public function __construct()
    {
        $this->current_year = date('Y');
        $this->prediction_model = new PredictionModel();
    }

    public function index(): void
    {
        for ($year = 2014; $year < $this->current_year; $year++) {
            for ($month = 0; $month < 12; $month++) {
                for ($zodiac = 0; $zodiac < 12; $zodiac++) {
                    $goroscop = $this->getPrediction($year, $month, $zodiac);
                    $this->prediction_model->insertPrediction($goroscop);
                }
            }
        }
    }

    private function getPrediction(int $year, int $month, int $zodiac): array
    {
        $data = array(
            'module' => 'goroskop',
            'action' => 'getcal',
            'y' => $year,
            'm' => $month,
            'zid' => $zodiac
        );
        $data = http_build_query($data);

        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . 'Content-Length: ' . strlen($data) . "\r\n",
                'content' => $data
            )
        );
        $goroscop = file_get_contents('http://moygoroskop.com/telets', false, stream_context_create($context_options));
        return json_decode($goroscop, true);
    }
}