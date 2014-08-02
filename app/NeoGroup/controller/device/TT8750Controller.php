<?php

namespace NeoGroup\controller\device;

use DateTime;
use Exception;
use NeoGroup\model\Device;
use NeoGroup\model\FuelPositionReport;
use NeoGroup\model\PositionReport;
use NeoGroup\model\ReportType;

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
                    $report->setOdometer($odometer);
                    $report->insert();
                }
                break;
            case self::DATAGRAMTYPE_SERIALPORT:
                $this->getLogger()->info("Serial port received: $datagram");
                $datagram = substr($datagram, 7);
                $datagram = hex2bin($datagram);
                $deviceId = intval((substr($datagram, 4, 8)));
                $eventId = ord($datagram{3}) + (ord($datagram{2}) << 8);
                $ios = ord($datagram{13}) + (ord($datagram{12}) << 8);
                $validity = ord($datagram{14});
                $latitude = $this->getCoordinate(substr($datagram, 15, 4));
                $longitude = $this->getCoordinate(substr($datagram, 19, 4));
                $speed = (intval(ord($datagram{24}) + (ord($datagram{23}) << 8))/10) * 1.8;
                $course = (intval(ord($datagram{26}) + (ord($datagram{25}) << 8))/10);
                $altitude = (ord($datagram{29}) + (ord($datagram{28}) << 8) + (ord($datagram{27}) << 16)) / 10;
                $odometer = ord($datagram{33}) + (ord($datagram{32}) << 8) + (ord($datagram{31}) << 16) + (ord($datagram{30}) << 24);
                $date = new DateTime();
                $date->setDate(ord($datagram{34})+2000, ord($datagram{35}), ord($datagram{36}));
                $date->setTime(ord($datagram{37}), ord($datagram{38}), ord($datagram{39}));
                $reporttype = $this->getReportTypeByEvent($eventId);
                
                switch ($reporttype)
                {
                    case ReportType::REPORTTYPE_FUELREPORT:
                        $report = new FuelPositionReport();
                        $report->setDevice(new Device($deviceId));
                        $report->setReportType(new ReportType($reporttype));
                        $report->setLongitude($longitude);
                        $report->setLatitude($latitude);
                        $report->setAltitude($altitude);
                        $report->setSpeed($speed);
                        $report->setCourse($course);
                        $report->setDate($date);
                        $report->setOdometer($odometer);
                        $this->getLogger()->info("report a insertar: " . print_r($report, true));
//                        $report->insert();
                        break;
                }
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
            case 1: $reportType = ReportType::REPORTTYPE_FUELREPORT; break;
            case 21: $reportType = ReportType::REPORTTYPE_TIMEREPORT; break;
            default: throw new Exception("Event \"$eventId\" not found");
        }
        return $reportType;
    }
}

?>