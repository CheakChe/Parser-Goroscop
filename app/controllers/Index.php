<?php


class Index extends AbstractController
{
    private $current_year;
    private $current_month;
    private $prediction_model;
    private $url;

    public function __construct()
    {
        $this->current_year = (int)date('Y');
        $this->current_month = (int)date('m');
        $this->url = explode('/', $_SERVER['REQUEST_URI']);
        $this->prediction_model = new PredictionModel();
    }

    public function index(): void
    {
        if ($this->url[2] === 'refresh') {
            $this->prediction_model->clearParse();
            $this->fromPrediction(2014);
        } else {
            $this->fromPrediction($this->current_year, $this->current_month);
        }
    }

    private function fromPrediction(int $start_year, int $start_month = 1): void
    {
        for ($year = $start_year; $year <= $this->current_year; $year++) {
            for ($month = $start_month; $month <= 12; $month++) {
                if ($year === $this->current_year && $month > $this->current_month) {
                    break;
                }
                for ($zodiac = 1; $zodiac <= 12; $zodiac++) {
                    $goroscop = $this->getPrediction($year, $month, $zodiac);
                    if ($goroscop['error'] === 0 && !empty($goroscop['static'])) {
                        $this->prediction_model->Prediction($goroscop, $year, $month, $zodiac);
                    }
                    Log::writeLog('Закончил парсить ' . $zodiac . ' зодиак в ' . $month . ' месяце в ' . $year . ' году' . PHP_EOL);
                }
                Log::writeLog('Закончил парсить ' . $month . ' месяц в ' . $year . ' году' . PHP_EOL);
            }
            Log::writeLog('Закончил парсить ' . $year . ' год' . PHP_EOL);
        }
        Log::writeLog('Закончил парсить ' . PHP_EOL);
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