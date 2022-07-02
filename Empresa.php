<?php
class Empresa
{
    private $idEmpresa;
    private $eNombre;
    private $eDireccion;

    private $mensajeoperacion;

    /** ###################'Funciones'#################### */

    public function cargar($idEmpresa, $eNombre, $eDireccion)
    {
        $this->setIdEmpresa($idEmpresa);
        $this->setENombre($eNombre);
        $this->setEDireccion($eDireccion);
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(
            enombre, 
            edireccion
            ) VALUES(
            '" . $this->getENombre() . "',
            '" . $this->getEDireccion() . "'
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
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresa = "SELECT * FROM empresa ";
        if ($condicion != "") {
            $consultaEmpresa = $consultaEmpresa . ' WHERE ' . $condicion;
        }
        $consultaEmpresa .= " ORDER BY enombre ";
        //echo $consultaEmpresa;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                $arregloEmpresa = array();
                while ($row2 = $base->Registro()) {

                    $idEmpresa = $row2['idempresa'];
                    $ENombre = $row2['enombre'];
                    $EDireccion = $row2['edireccion'];

                    $empresa = new Empresa();
                    $empresa->cargar($idEmpresa, $ENombre, $EDireccion);
                    array_push($arregloEmpresa, $empresa);
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $arregloEmpresa;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE empresa SET 
        enombre = '" . $this->getENombre() . "',
        edireccion = '" . $this->getEDireccion() . "',
        WHERE idempresa = " . $this->getIdEmpresa();

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
            $consultaBorra = "DELETE FROM empresa WHERE idempresa = " . $this->getIdEmpresa();
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

    public function Buscar($idempresa)
    {
        $base = new BaseDatos();
        $consultaEmpresa = "SELECT * FROM empresa WHERE idempresa = " . $idempresa;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdEmpresa($idempresa);
                    $this->setENombre($row2['enombre']);
                    $this->setEDireccion($row2['edireccion']);
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

    public function ultimoId()
    {
        $base = new BaseDatos();
        $consulta = "SELECT MAX(idempresa) AS idempresa FROM empresa";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $idEmpresa = $row2['idempresa'];
                }
            }
        }
        return $idEmpresa;
    }

    public function __construct()
    {
        $this->setIdEmpresa("");
        $this->setENombre("");
        $this->setEDireccion("");
    }


    public function __toString()
    {
        $string = " ID Empresa: " . $this->getIdEmpresa() . "\n";
        $string .= " Nombre Empresa: " . $this->getENombre() . "\n";
        $string .= " Direccion Empresa: " . $this->getEDireccion() . "\n";

        return $string;
    }
    /** ###################'Getters & Setters'#################### */

    /**
     * Get the value of idEmpresa
     */
    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    /**
     * Set the value of idEmpresa
     *
     * @return  self
     */
    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;

        return $this;
    }

    /**
     * Get the value of eNombre
     */
    public function getENombre()
    {
        return $this->eNombre;
    }

    /**
     * Set the value of eNombre
     *
     * @return  self
     */
    public function setENombre($eNombre)
    {
        $this->eNombre = $eNombre;

        return $this;
    }

    /**
     * Get the value of eDireccion
     */
    public function getEDireccion()
    {
        return $this->eDireccion;
    }

    /**
     * Set the value of eDireccion
     *
     * @return  self
     */
    public function setEDireccion($eDireccion)
    {
        $this->eDireccion = $eDireccion;

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
