<?php

namespace Mamba\controller\device;

class STD900Controller extends DeviceController
{
    public function notifyPackageReceived($data, &$deviceId)
    {
        $response = null;
        $dataReceived = $package->getData();
        if (substr($dataReceived, 0, 3) == "<ID" && substr($dataReceived, -1, 1) == ">") 
        {
            $deviceId = intval((substr($dataReceived, 3, 6)));
            $response = ">AK<";
        }
        return $response;
    }
    
    private function datetimeFromSAIP($GPSTime, $GPSWeek = "", $GPSWeekDay = "")
    {
        $hours = $GPSTime / 3600;
        $time['hours'] = floor($hours);

        $minutes = ($hours - $time['hours']) * 60;
        $time['minutes'] = floor($minutes);

        $seconds = ($minutes - $time['minutes']) * 60;
        $time['seconds'] = round($seconds);

        if ($time['seconds'] == 60) {
            $time['minutes']++;
            $time['seconds'] = 0;
        }

        if (strlen($GPSWeek) > 0) {
            $totalDias = ($GPSWeek * 7) + $GPSWeekDay;
            $hoy = mktime(0, 0, 0, 1, 6 + $totalDias, 1980);
            $time['date'] = date("Y-m-d", $hoy);
        } else {
            //Fecha actual UTC
            $date = getdate();
            $time['date'] = $date['year'] . "-" . $date['mon'] . "-" . $date['mday'];
        }
        return $time['date'] . " " . $time['hours'] . ":" . $time['minutes'] . ":" . $time['seconds'];
    }
    
    private function coordSAIP($coord, $tipo = "lat")
    {
        $offset = ($tipo == "lat") ? 3 : 4;
        return substr($coord, 0, $offset) . "." . substr($coord, -5);
    }
    
    private function miles2kms($miles) {
        return $miles * 1.6093;
    }
    
    private function eventosMapper($code, $comando = "REV")
    {
        //Valor por defecto en caso que el evento no este definido
        $scriptId = $this->getScriptId($this->_equipoid);
        $eventos[$code] = null;
        switch ($comando) {
            case "REV":
                $eventos['00'] = 4; // reporte por distancia
                $eventos['01'] = 2; // reporte por tiempo
                $eventos['02'] = 1; // respuesta a pedido de posición
                $eventos['03'] = 10; // zona de desasignaci�n autom�tica
                $eventos['04'] = 6; // inicio de movil detenido
                $eventos['05'] = 20; // cierre de puerta de cabina
                $eventos['06'] = 8; // inicio de sobrevelocidad
                $eventos['07'] = 4; // reporte por distancia
                $eventos['09'] = 61; // volquete abajo
                $eventos['10'] = 27; // consumo de combustible
                $eventos['11'] = 9; // fin de sobrevelocidad
                $eventos['12'] = 144; // apertura de gabinete de equipo
                $eventos['13'] = 145; // cierre de gabinete de equipo
                $eventos['14'] = 5; // reporte por distancia en zona
                $eventos['15'] = 3; // reporte por tiempo en zona
                $eventos['16'] = 7; // fin de movil detenido
                $eventos['18'] = 41; // apertura de cisterna nro 1
                $eventos['19'] = 42; // apertura de cisterna nro 2
                $eventos['20'] = 156; // GPS Open
                $eventos['21'] = 157; // GPS Shortcut
                $eventos['22'] = 57; // apertura de cajón de válvulas
                $eventos['23'] = 151; // Desconexión de alimentación externa evento
                $eventos['24'] = 14; // desactivación bypass
                $eventos['25'] = 13; // activación bypass
                $eventos['26'] = 60; // volquete arriba
                $eventos['27'] = 40; // reconexión de alimentación externa
                $eventos['28'] = ($scriptId==537 )?388:15; // Sabotaje a Sistema de Control | apertura de puerta de carga alarma
                $eventos['29'] = 17; // apertura de puerta de carga
                $eventos['30'] = 12; // evento de robo (no entra como alarma)
                $eventos['31'] = 34; // tercer eje abajo
                $eventos['32'] = 35; // dep�sito de residuos lleno
                $eventos['33'] = ($scriptId==537 )?389:16; // Sistema de Control Normal | cierre de puerta de carga
                $eventos['34'] = 26; // desenganche de semiremolque
                $eventos['35'] = 11; // alarma de robo
                $eventos['36'] = 23; // enganche de semiremolque
                $eventos['37'] = 37; // inicio de actividad
                $eventos['38'] = 38; // fin de actividad
                $eventos['39'] = 24; // desenganche de semiremolque alarma
                $eventos['40'] = 28; // temperatura alta cámara 1
                $eventos['41'] = 29; // temperatura normal cámara 1
                $eventos['42'] = 30; // temperatura baja cámara 1
                $eventos['43'] = 29; // temperatura normal cámara 1
                $eventos['44'] = 19; // apertura de puerta de cabina alarma
                $eventos['45'] = 21; // apertura de puerta de cabina
                $eventos['47'] = 31; // temperatura alta cámara 2
                $eventos['48'] = 33; // temperatura baja cámara 2
                $eventos['49'] = 32; // temperatura normal cámara 2
                $eventos['50'] = 62; // entrega de distribuci�n urbana
                $eventos['51'] = 22; // cierre de puerta de cabina
                $eventos['52'] = 43; // apertura de cisterna nro 3
                $eventos['53'] = 44; // apertura de cisterna nro 4
                $eventos['54'] = 45; // apertura de cisterna nro 5
                $eventos['55'] = 46; // apertura de cisterna nro 6
                $eventos['56'] = 59; // tercer eje arriba
                $eventos['58'] = 36; // dep�sito de residuos vac�o (Caja vac�a)
                $eventos['59'] = 63; // no Entrega de distribuci�n urbana
                $eventos['60'] = 35; // depósito de residuos lleno (Caja llena)
                $eventos['61'] = 40; // reconexión de alimentación externa
                $eventos['63'] = 53; // cajón de válvulas violado
                $eventos['64'] = 39; // desconexión de alimentación externa
                $eventos['65'] = 146; // apertura de gabinete de cuentalitros
                $eventos['66'] = 147; // cierre de gabinete de cuentalitros
                $eventos['67'] = 37; // inicio de actividad
                $eventos['68'] = 38; // fin de actividad
                $eventos['69'] = 163; // Contacto ON
                $eventos['70'] = 164; // Contacto OFF   
                $eventos['71'] = 160; // Apertura de puerta de pasajero
                $eventos['72'] = 161; // Apertura de puerta de pasajero evento
                $eventos['73'] = 162; // Cierre de puerta de pasajero evento
                $eventos['76'] = 47; // cierre de cisterna nro 1
                $eventos['77'] = 48; // cierre de cisterna nro 2
                $eventos['78'] = 49; // cierre de cisterna nro 3
                $eventos['79'] = 50; // cierre de cisterna nro 4
                $eventos['80'] = 51; // cierre de cisterna nro 5
                $eventos['81'] = 52; // cierre de cisterna nro 6
                $eventos['82'] = 64; // inicio de exceso de RPM
                $eventos['83'] = 65; // fin de exceso de RPM
                $eventos['84'] = 66; // aceleraci�n brusca
                $eventos['85'] = 67; // frenada brusca
                $eventos['86'] = 68; // aviso de accidente
                $eventos['87'] = 69; // bloqueo GPS
                $eventos['88'] = 75; // entrada en zona de riesgo
                $eventos['89'] = 76; // salida de zona de riesgo
                $eventos['90'] = 99; // inicio descarga de hormigón
                $eventos['91'] = 100; // fin descarga de hormigón
                $eventos['92'] = 101; // inicio mezcla de hormigón
                $eventos['93'] = 102; // fin mezcla de hormigón
                $eventos['94'] = 86; // activacion de alarma
                $eventos['95'] = 88; // desactivaci�n bypass2
                $eventos['96'] = 87; // activaci�n bypass2
                $eventos['97'] = 90; // reconexion de TSS
                $eventos['98'] = 91; // desconexion de TSS
                $eventos['99'] = 92; // radio baliza
                $eventos['A0'] = 98; // falla sensores hormig�n
                $eventos['A1'] = 2; // reporte por tiempo hibrido
                $eventos['A2'] = 122; // Desconexi�n  sensor temperatura
                $eventos['A3'] = 119; // Temperatura m�xima camara 3
                $eventos['A4'] = 120; // Temperatura normal camara 3
                $eventos['A5'] = 121; // Temperatura m�nima camara 3
                $eventos['A9'] = 113; // Temperatura m�xima camara 1
                $eventos['B0'] = 114; // Temperatura normal camara 1
                $eventos['B1'] = 115; // Temperatura m�nima camara 1
                $eventos['B2'] = 116; // Temperatura m�xima camara 2
                $eventos['B3'] = 117; // Temperatura normal camara 2
                $eventos['B4'] = 118; // Temperatura m�nima camara 2
                $eventos['B7'] = 139; // Desconexi�n comp a bordo
                $eventos['B8'] = 142; // Bater�a Backup baja
                $eventos['B9'] = 196; // Detección de inhibición celular Baja
                $eventos['BA'] = 197; // Detección de inhibición celular Media
                $eventos['BB'] = 198; // Detección de inhibición celular Alta
                $eventos['BC'] = 130; // Lectura de c�digo de barra
                $eventos['C1'] = 149; // Solenoide Activado
                $eventos['C2'] = 150; // Solenoide Desactivado
                $eventos['C3'] = 151; // Desconexión de alimentación externa evento
                $eventos['C4'] = 152; // Descanso nocturno insuficiente
                $eventos['C5'] = 153; // Conducción en horario no permitido
                $eventos['C6'] = 154; // Tiempo de conducción diaria excedido
                $eventos['C7'] = 155; // Tiempo de conducción nocturna excedido
                $eventos['C8'] = 190; // Inicio de exceso de conducción
                $eventos['C9'] = 191; // Fin de exceso de conducción
                $eventos['CA'] = 192; // Inico de exceso tiempo de ralenti
                $eventos['CB'] = 183; // Exceso tiempo detenido con contacto evento
                $eventos['CC'] = 193; // Fin de exceso tiempo de ralenti
                $eventos['CE'] = 173;// apertura de cisterna nro 7
                $eventos['CF'] = 174;// apertura de cisterna nro 8
                $eventos['CG'] = 175;// cierre de cisterna nro 7
                $eventos['CH'] = 176;// cierre de cisterna nro 8
                $eventos['CI'] = 184; // Desconexión terminal mensajería
                $eventos['CJ'] = 185; // Reconexión terminal mensajería
                $eventos['CM'] = 194; // Identificaci�n chofer
                $eventos['CO'] = 221;  // Fuera de Ruta Equipo
                $eventos['CP'] = 222;  // Dentro de Ruta Equipo                
                $eventos['CQ'] = 225;  // Cambio de Sentido
                $eventos['CR'] = 227;  // Rendimiento Consulta
                $eventos['CT'] = 229;  //Conexi�n D+
                $eventos['CU'] = 228;  // Desconexi�n D+
                $eventos['CV'] = 237;  //Apertura de puerta de chofer alarma
                $eventos['CW'] = 238;  //Cierre de puerta de chofer
                $eventos['CX'] = 239;  //Apertura de puerta de chofer evento
                $eventos['CY'] = 233;  //Entrada en zona PPA
                $eventos['CZ'] = 234;  //Salida de zona PPA
                $eventos['D1'] = 235;  //Entrada en zona PEC
                $eventos['D2'] = 236;  //Salida de zona PEC
                $eventos['D3'] = 204;  //Apertura de panel
                $eventos['D4'] = 243;  //Sensor de lluvia activado
                $eventos['D5'] = 244;  //Sensor de lluvia desaactivado
		$eventos['D6'] = 248;  //GPS log
		$eventos['D8'] = 256;  // Ventanilla abierta
		$eventos['D9'] = 257;  // Ventanilla abierta
		$eventos['DA'] = 258;  // Barra de válvulas abiertas
		$eventos['DB'] = 259;  // Barra de válvulas cerradas
                $eventos['DC'] = 158;  // Envio de posición
                $eventos['DD'] = 302;  //Entrada en zona PPA (Evento)
                $eventos['DE'] = 303;  //Salida de zona PPA (Evento)
                $eventos['DF'] = 304;  //Entrada en zona PEC (Evento)
                $eventos['DG'] = 305;  //Salida de zona PEC (Evento)
                $eventos['DH'] = 306;  // Fuera de Ruta Equipo (Evento)
                $eventos['DI'] = 307;  // Dentro de Ruta Equipo (Evento)
                $eventos['DJ'] = 308;  // Cambio de Sentido (Evento)
                $eventos['X1'] = 130; // Lectura de c�digo de barra
                $eventos['X3'] = 148; // Cuentalitros
                $eventos['X4'] = 177; // Macro
                $eventos['X5'] = 54;  // Mensajeria libre
                $eventos['X6'] = 55;  // Mensajeria fija
                $eventos['X7'] = 285;  // Telemetría
                break;

            case "RTX":
                $eventos[']'] = 54; // mensajer�a libre (compatibilidad con 2G)
                $eventos['#'] = 55; // mensajer�a fija (compatibilidad con 2G)
                $eventos['00'] = 54; // mensajer�a libre (compatibilidad con 2G)
                $eventos['01'] = 55; // mensajer�a fija (compatibilidad con 2G)
                $eventos['04'] = 54; // mensajer�a libre
                $eventos['05'] = 55; // mensajer�a fija
                break;

            case "RAC":
                $eventos['00'] = 72; // actividad Diaria
                $eventos['01'] = 73; // actividad Local
                $eventos['02'] = 74; // actividad Remota
                break;

            case "RCR":
                $eventos['00'] = 226; // rendimiento Periodico
                $eventos['01'] = 227; // rendimiento Consulta
                break;
            
            case "RCS":
                $eventos['00'] = 226; // rendimiento Periodico
                $eventos['01'] = 227; // rendimiento Consulta
                $eventos['03'] = 242; // rendimiento Consulta Totales
                break;    
                
            case "RCA":
                $eventos['00'] = 103; // consumo combustible Diario
                $eventos['02'] = 104; // consumo combustible Remoto
                $eventos['03'] = 292; // consumo combustible Zona
                break;

            case "RTE":
                $eventos['00'] = 110; // Reporte de Temperatura por Tiempo
                $eventos['01'] = 112; // Reporte de Temperatura por Evento
                $eventos['02'] = 111; // Reporte de Temperatura por Consulta
                break;
        }

        return $eventos[$code];
    }
}

?>