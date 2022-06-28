<?php
class Pasajero
{
    private $rdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $idviaje;

    private $mensajeoperacion;

    /** ###################'Funciones'#################### */

    public function cargar($rdocumento, $pnombre, $papellido, $ptelefono, $idviaje)
    {
        $this->setRdocumento($rdocumento);
        $this->setPnombre($pnombre);
        $this->setPapellido($papellido);
        $this->setPtelefono($ptelefono);
        $this->setIdviaje($idviaje);
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
            '" . $this->getIdviaje() . "'
            )";
        if ($base->Iniciar()) {

            if ($base->Ejecutar($consultaInsertar)) {

                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
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

                    $NroDoc = $row2['rdocumento'];
                    $Nombre = $row2['pnombre'];
                    $Apellido = $row2['papellido'];
                    $Telefono = $row2['ptelefono'];
                    $IdViaje = $row2['idviaje'];

                    $pasajero = new Pasajero();
                    $pasajero->cargar($NroDoc, $Nombre, $Apellido, $Telefono, $IdViaje);
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

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE pasajero SET 
        pnombre = '" . $this->getPnombre() . "',
        papellido = '" . $this->getPapellido() . "',
        ptelefono = '" . $this->getPnombre() . "',
        idviaje = '" . $this->getIdviaje() . "',
        WHERE nrodoc = " . $this->getRdocumento();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }

        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM pasajero WHERE nrodoc = " . $this->getRdocumento();
            if ($base->Ejecutar($consultaBorra)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
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
                    $this->setRdocumento($rdocumento);
                    $this->setPnombre($row2['pnombre']);
                    $this->setPapellido($row2['papellido']);
                    $this->setPtelefono($row2['ptelefono']);
                    $this->setIdviaje($row2['idviaje']);
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
        $this->setIdviaje("");
    }

    public function __toString()
    {
        $string = "Documento: " . $this->getRdocumento() . "\n";
        $string .= "Nombre: " . $this->getPnombre() . "\n";
        $string .= "Apellido: " . $this->getPapellido() . "\n";
        $string .= "Teléfono: " . $this->getPtelefono() . "\n";
        $string .= $this->getIdviaje()->__toString() . "\n";

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
     * Get the value of idviaje
     */
    public function getIdviaje()
    {
        return $this->idviaje;
    }

    /**
     * Set the value of idviaje
     *
     * @return  self
     */
    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;

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
}
