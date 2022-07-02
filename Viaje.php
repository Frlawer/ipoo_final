<?php
class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $rdocumento;
    private $objEmpresa;
    private $objResponsable;
    private $vimporte;
    private $tipoAsiento;
    private $idayvuelta;

    private $mensajeOperacion;

    /** ###################'Funciones'#################### */

    public function cargar($idviaje, $vdestino, $vcantmaxpasajeros, $rdocumento, $objEmpresa, $objResponsable, $vimporte, $tipoAsiento, $idayvuelta)
    {
        $this->setIdviaje($idviaje);
        $this->setVdestino($vdestino);
        $this->setVcantmaxpasajeros($vcantmaxpasajeros);
        $this->setRdocumento($rdocumento);
        $this->setObjEmpresa($objEmpresa);
        $this->setObjResponsable($objResponsable);
        $this->setVimporte($vimporte);
        $this->setTipoAsiento($tipoAsiento);
        $this->setIdayvuelta($idayvuelta);
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO viaje(
            vdestino, 
            vcantmaxpasajeros, 
            rdocumento, 
            idempresa, 
            rnumeroempleado, 
            vimporte, 
            tipoAsiento, 
            idayvuelta
            ) VALUES(
                '" . $this->getVdestino() . "',
                '" . $this->getVcantmaxpasajeros() . "',
                '" . $this->getRdocumento() . "',
                '" . $this->getObjEmpresa()->getIdempresa() . "',
                '" . $this->getObjResponsable()->getRnumeroempleado() . "',
                '" . $this->getVimporte() . "',
                '" . $this->getTipoAsiento() . "',
                '" . $this->getIdayvuelta() . "'
                )";

        if ($base->Iniciar()) {

            if ($base->Ejecutar($consultaInsertar)) {

                $resp =  true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }


    public function listar($condicion = "")
    {
        $arregloViaje = null;
        $base = new BaseDatos();
        $consultaViaje = "SELECT * FROM viaje ";
        if ($condicion != "") {
            $consultaViaje = $consultaViaje . ' WHERE ' . $condicion;
        }
        $consultaViaje .= " ORDER BY vdestino ";
        //echo $consultaViaje;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViaje)) {
                $arregloViaje = array();
                while ($row2 = $base->Registro()) {

                    $empresa = new Empresa();
                    $empresa->Buscar($row2['idempresa']);
                    $responsable = new ResponsableV();
                    $responsable->Buscar($row2['rnumeroempleado']);

                    $idviaje = $row2['idviaje'];
                    $vdestino = $row2['vdestino'];
                    $vcantmaxpasajeros = $row2['vcantmaxpasajeros'];
                    $rdocumento = $row2['rdocumento'];
                    $idempresa = $empresa;
                    $rnumeroempleado = $responsable;
                    $vimporte = $row2['vimporte'];
                    $tipoAsiento = $row2['tipoAsiento'];
                    $idayvuelta = $row2['idayvuelta'];

                    $pasajero = new Viaje();
                    $pasajero->cargar($idviaje, $vdestino, $vcantmaxpasajeros, $rdocumento, $idempresa, $rnumeroempleado, $vimporte, $tipoAsiento, $idayvuelta);
                    array_push($arregloViaje, $pasajero);
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $arregloViaje;
    }

    public function modificar($idViaje)
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE viaje SET 
        idviaje = " . $this->getIdviaje() . ",
        vdestino = '" . $this->getVdestino() . "',
        vcantmaxpasajeros = " . $this->getVcantmaxpasajeros() . ",
        rdocumento = '" . $this->getRdocumento() . "',
        idempresa = " . $this->getObjEmpresa()->getIdempresa() . ",
        rnumeroempleado = " . $this->getObjResponsable()->getRnumeroempleado() . ",
        vimporte = " . $this->getVimporte() . ",
        tipoAsiento = " . $this->getTipoAsiento() . ",
        idayvuelta = " . $this->getIdayvuelta() . "
        WHERE idviaje = " . $idViaje;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp =  true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }

        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM viaje WHERE idviaje = " . $this->getIdviaje();
            if ($base->Ejecutar($consultaBorra)) {
                $resp =  true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }

        return $resp;
    }

    public function Buscar($idviaje)
    {
        $base = new BaseDatos();
        $consultaviaje = "SELECT * FROM viaje WHERE idviaje = " . $idviaje;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaviaje)) {
                if ($row2 = $base->Registro()) {
                    $responsable = new ResponsableV();
                    $responsable->Buscar($row2['rnumeroempleado']);
                    $empresa = new Empresa();
                    $empresa->Buscar($row2['idempresa']);

                    $this->setIdviaje($row2["idviaje"]);
                    $this->setVdestino($row2['vdestino']);
                    $this->setVcantmaxpasajeros($row2['vcantmaxpasajeros']);
                    $this->setRdocumento($row2['rdocumento']);
                    $this->setObjEmpresa($empresa);
                    $this->setObjResponsable($responsable);
                    $this->setVimporte($row2['vimporte']);
                    $this->setTipoAsiento($row2['tipoAsiento']);
                    $this->setIdayvuelta($row2['idayvuelta']);
                    $resp = true;
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
            return $resp;
        }
    }

    public function __construct()
    {
        $this->setIdviaje("");
        $this->setVdestino("");
        $this->setVcantmaxpasajeros("");
        $this->setRdocumento("");
        $this->setObjEmpresa("");
        $this->setObjResponsable("");
        $this->setVimporte("");
        $this->setTipoAsiento("");
        $this->setIdayvuelta("");

        $this->setMensajeOperacion("");
    }

    public function __toString()
    {
        $string = "ID: " . $this->getIdviaje() . "\n";
        $string .= "Destino: " . $this->getVdestino() . "\n";
        $string .= "Cantidad Maxima de Pasajeros: " . $this->getVcantmaxpasajeros() . "\n";
        $string .= "- Empresa: \n"  . $this->getObjEmpresa()->__toString() . "\n";
        $string .= "- Responsable: \n" . $this->getObjResponsable()->__toString() . "\n";
        $string .= "Importe: " . $this->getVimporte() . "\n";
        $string .= "Tipo de Asiento: " . $this->getTipoAsiento() . "\n";
        $string .= "Ida y Vuelta: " . $this->getIdayvuelta() . "\n";

        return $string;
    }
    /** ###################'Getters & Setters'#################### */


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
     * Get the value of vdestino
     */
    public function getVdestino()
    {
        return $this->vdestino;
    }

    /**
     * Set the value of vdestino
     *
     * @return  self
     */
    public function setVdestino($vdestino)
    {
        $this->vdestino = $vdestino;

        return $this;
    }

    /**
     * Get the value of vcantmaxpasajeros
     */
    public function getVcantmaxpasajeros()
    {
        return $this->vcantmaxpasajeros;
    }

    /**
     * Set the value of vcantmaxpasajeros
     *
     * @return  self
     */
    public function setVcantmaxpasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;

        return $this;
    }

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
     * Get the value of idempresa
     */
    public function getIdempresa()
    {
        return $this->idempresa;
    }

    /**
     * Set the value of idempresa
     *
     * @return  self
     */
    public function setIdempresa($idempresa)
    {
        $this->idempresa = $idempresa;

        return $this;
    }

    /**
     * Get the value of rnumeroempleado
     */
    public function getRnumeroempleado()
    {
        return $this->rnumeroempleado;
    }

    /**
     * Set the value of rnumeroempleado
     *
     * @return  self
     */
    public function setRnumeroempleado($rnumeroempleado)
    {
        $this->rnumeroempleado = $rnumeroempleado;

        return $this;
    }

    /**
     * Get the value of vimporte
     */
    public function getVimporte()
    {
        return $this->vimporte;
    }

    /**
     * Set the value of vimporte
     *
     * @return  self
     */
    public function setVimporte($vimporte)
    {
        $this->vimporte = $vimporte;

        return $this;
    }

    /**
     * Get the value of tipoAsiento
     */
    public function getTipoAsiento()
    {
        return $this->tipoAsiento;
    }

    /**
     * Set the value of tipoAsiento
     *
     * @return  self
     */
    public function setTipoAsiento($tipoAsiento)
    {
        $this->tipoAsiento = $tipoAsiento;

        return $this;
    }

    /**
     * Get the value of idayvuelta
     */
    public function getIdayvuelta()
    {
        return $this->idayvuelta;
    }

    /**
     * Set the value of idayvuelta
     *
     * @return  self
     */
    public function setIdayvuelta($idayvuelta)
    {
        $this->idayvuelta = $idayvuelta;

        return $this;
    }

    /**
     * Get the value of mensajeOperacion
     */
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    /**
     * Set the value of mensajeOperacion
     *
     * @return  self
     */
    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;

        return $this;
    }

    /**
     * Get the value of objEmpresa
     */
    public function getObjEmpresa()
    {
        return $this->objEmpresa;
    }

    /**
     * Set the value of objEmpresa
     *
     * @return  self
     */
    public function setObjEmpresa($objEmpresa)
    {
        $this->objEmpresa = $objEmpresa;

        return $this;
    }

    /**
     * Get the value of objResponsable
     */
    public function getObjResponsable()
    {
        return $this->objResponsable;
    }

    /**
     * Set the value of objResponsable
     *
     * @return  self
     */
    public function setObjResponsable($objResponsable)
    {
        $this->objResponsable = $objResponsable;

        return $this;
    }
}
