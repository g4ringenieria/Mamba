<?php

namespace NeoGroup\controller\device;

class TT8750Controller extends DeviceController
{
    const DATAGRAMTYPE_DEFAULT = 8;
    const DATAGRAMTYPE_SERIALPORT = 2;
    
    protected function __construct()
    {
        parent::__construct(8000);
    }
    
    public function notifyPackageReceived(DevicePackage $package)
    {
        $responsePackage = null;
        $datagram = $package->getData();
        $datagramType = ord($datagram{4});
        switch ($datagramType)
        {
            case self::DATAGRAMTYPE_DEFAULT:
                if (ord($datagram{5}) == 0)
                {
                    $deviceId = intval((substr($datagram, 11, 21)));
                    $responsePackage = new DevicePackage($deviceId, "");
                }
                else
                {
                    $deviceId = intval((substr($datagram, 15, 8)));
                    $eventId = ord($datagram{14}) + (ord($datagram{13}) << 8);
                    $ios = ord($datagram{24}) + (ord($datagram{23}) << 8);
                    $validity = ord($datagram{25});
                    $latitude = $this->getCoordinate(substr($datagram, 26, 4));
                    $longitude = $this->getCoordinate(substr($datagram, 30, 4));
                    $speed = (intval(ord($datagram{35}) + (ord($datagram{34}) << 8))/10) * 1.8;
                    $course = (intval(ord($datagram{37}) + (ord($datagram{36}) << 8))/10);
                    $altitude = (ord($datagram{40}) + (ord($datagram{39}) << 8) + (ord($datagram{38}) << 16)) / 10;
                    $odometer = ord($datagram{44}) + (ord($datagram{43}) << 8) + (ord($datagram{42}) << 16) + (ord($datagram{41}) << 24);
                    $date = new DateTime();
                    $date->setDate(ord($datagram{45})+2000, ord($datagram{46}), ord($datagram{47}));
                    $date->setTime(ord($datagram{48}), ord($datagram{49}), ord($datagram{50}));
                    $report = new PositionReport();
                    $report->setDevice(new Device($deviceId));
                    $report->setReportType(new ReportType($this->getReportTypeByEvent($eventId)));
                    $report->setLongitude($longitude);
                    $report->setLatitude($latitude);
                    $report->setAltitude($altitude);
                    $report->setSpeed($speed);
                    $report->setCourse($course);
                    $report->setDate($date);
                    $report->setInputDate(new DateTime());
                    $report->setOdometer($odometer);
                    $report->insert();
                }
                break;
            case self::DATAGRAMTYPE_SERIALPORT:
                $datagram = substr($datagram, 7);
		$datagram = "0035000a08100418e0be4f" . $datagram;
                $datagram = hex2bin($datagram);
                $deviceId = intval((substr($datagram, 15, 8)));
                $eventId = ord($datagram{14}) + (ord($datagram{13}) << 8);
                $this->getLogger()->debug("Serial port package => equipo: " . $deviceId . "; evento: " . $eventId);
                break;
        }
    }
    
    private function getCoordinate ($coordinateField)
    {
        $isNegative = false;
        $a = ord($coordinateField{0});
        $b = ord($coordinateField{1});
        $c = ord($coordinateField{2});
        $d = ord($coordinateField{3});
        if ($a > 128)
        {
            $isNegative = true;
            $a = 255 - $a;
            $b = 255 - $b;
            $c = 255 - $c;
            $d = 255 - $d;
        }
        $decimalCoordinate = (string)($d + ($c << 8) + ($b << 16) + ($a << 24));
        $decimalCoordinate = str_pad($decimalCoordinate, 8, "0", STR_PAD_LEFT); 
        $degrees = floatval(substr($decimalCoordinate, 0, strlen($decimalCoordinate) - 6));
        $minutes = floatval(substr($decimalCoordinate, -6)) / 10000;
        $coordinateDegrees = $degrees + ($minutes/60);
        return ($isNegative)? -$coordinateDegrees : $coordinateDegrees;
    }
    
    private function getReportTypeByEvent ($eventId)
    {
        $reportType = 0;
        switch ($eventId)
        {
            case 21: $reportType = ReportType::REPORTTYPE_TIMEREPORT; break;
        }
        return $reportType;
    }
}

?>