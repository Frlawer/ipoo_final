<?php
class Pasajero
{
    private $rdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $objViaje;

    private $mensajeoperacion;

    /** ###################'Funciones'#################### */

    public function cargar($rdocumento, $pnombre, $papellido, $ptelefono, $objViaje)
    {
        $this->setRdocumento($rdocumento);
        $this->setPnombre($pnombre);
        $this->setPapellido($papellido);
        $this->setPtelefono($ptelefono);
        $this->setObjViaje($objViaje);
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero(
            rdocumento, 
            pnombre, 
            papellido, 
            ptelefono, 
            idviaje
            )VALUES(
            '" . $this->getRdocumento() . "',
            '" . $this->getPnombre() . "',
            '" . $this->getPapellido() . "',
            '" . $this->getPtelefono() . "',
            '" . $this->getObjViaje()->getIdviaje() . "'
            )";
        if ($base->Iniciar()) {

            if ($base->Ejecutar($consultaInsertar)) {

                $resp =  true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }


    public function listar($condicion = "")
    {
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero ";
        if ($condicion != "") {
            $consultaPasajero = $consultaPasajero . ' WHERE ' . $condicion;
        }
        $consultaPasajero .= " ORDER BY papellido ";
        //echo $consultaPasajero;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajero = array();
                while ($row2 = $base->Registro()) {

                    $viaje = new Viaje();
                    $viaje->Buscar($row2['idviaje']);

                    $NroDoc = $row2['rdocumento'];
                    $Nombre = $row2['pnombre'];
                    $Apellido = $row2['papellido'];
                    $Telefono = $row2['ptelefono'];

                    $pasajero = new Pasajero();
                    $pasajero->cargar($NroDoc, $Nombre, $Apellido, $Telefono, $viaje);
                    array_push($arregloPasajero, $pasajero);
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $arregloPasajero;
    }

    public function modificar($DNIPasajero)
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE `pasajero` SET 
        `rdocumento` = " . $this->getRdocumento() . ", 
        `pnombre` = '" . $this->getPnombre() . "', 
        `papellido` = '" . $this->getPapellido() . "', 
        `ptelefono` = " . $this->getPnombre() . ", 
        `idviaje` = " . $this->getObjViaje()->getIdviaje() . " 
        WHERE `rdocumento` = " . $DNIPasajero;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp =  true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }

        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM pasajero WHERE rdocumento = " . $this->getRdocumento();
            if ($base->Ejecutar($consultaBorra)) {
                $resp =  true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }

        return $resp;
    }

    public function Buscar($rdocumento)
    {
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero WHERE rdocumento = " . $rdocumento;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                if ($row2 = $base->Registro()) {
                    $viaje = new Viaje();
                    $viaje->Buscar($row2['idviaje']);
                    $this->setRdocumento($rdocumento);
                    $this->setPnombre($row2['pnombre']);
                    $this->setPapellido($row2['papellido']);
                    $this->setPtelefono($row2['ptelefono']);
                    $this->setObjViaje($viaje);
                    $resp = true;
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function __construct()
    {
        $this->setRdocumento("");
        $this->setPnombre("");
        $this->setPapellido("");
        $this->setPtelefono("");
        $this->setObjViaje("");
    }

    public function __toString()
    {
        $string = "Documento: " . $this->getRdocumento() . "\n";
        $string .= "Nombre: " . $this->getPnombre() . "\n";
        $string .= "Apellido: " . $this->getPapellido() . "\n";
        $string .= "Teléfono: " . $this->getPtelefono() . "\n";
        $string .= "Viaje asignado: \n " . $this->getObjViaje()->__toString() . "\n";

        return $string;
    }

    /** ###################'Getters & Setters'#################### */
    /**
     * Get the value of rdocumento
     */
    public function getRdocumento()
    {
        return $this->rdocumento;
    }

    /**
     * Set the value of rdocumento
     *
     * @return  self
     */
    public function setRdocumento($rdocumento)
    {
        $this->rdocumento = $rdocumento;

        return $this;
    }

    /**
     * Get the value of pnombre
     */
    public function getPnombre()
    {
        return $this->pnombre;
    }

    /**
     * Set the value of pnombre
     *
     * @return  self
     */
    public function setPnombre($pnombre)
    {
        $this->pnombre = $pnombre;

        return $this;
    }

    /**
     * Get the value of papellido
     */
    public function getPapellido()
    {
        return $this->papellido;
    }

    /**
     * Set the value of papellido
     *
     * @return  self
     */
    public function setPapellido($papellido)
    {
        $this->papellido = $papellido;

        return $this;
    }

    /**
     * Get the value of ptelefono
     */
    public function getPtelefono()
    {
        return $this->ptelefono;
    }

    /**
     * Set the value of ptelefono
     *
     * @return  self
     */
    public function setPtelefono($ptelefono)
    {
        $this->ptelefono = $ptelefono;

        return $this;
    }

    /**
     * Get the value of mensajeoperacion
     */
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    /**
     * Set the value of mensajeoperacion
     *
     * @return  self
     */
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;

        return $this;
    }

    /**
     * Get the value of objViaje
     */
    public function getObjViaje()
    {
        return $this->objViaje;
    }

    /**
     * Set the value of objViaje
     *
     * @return  self
     */
    public function setObjViaje($objViaje)
    {
        $this->objViaje = $objViaje;

        return $this;
    }
}
