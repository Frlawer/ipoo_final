<?php
class ResponsableV
{

    private $rnumeroempleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;

    private $mensajeOperacion;

    /** ###################'Funciones'#################### */

    public function cargar($rnumeroempleado, $rnumerolicencia, $rnombre, $rapellido)
    {
        $this->setRnumeroempleado($rnumeroempleado);
        $this->setRnumerolicencia($rnumerolicencia);
        $this->setRnombre($rnombre);
        $this->setRapellido($rapellido);
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO responsable(
            rnumerolicencia, 
            rnombre, 
            rapellido
            ) VALUES(
            " . $this->getRnumerolicencia() . ",
            '" . $this->getRnombre() . "',
            '" . $this->getRapellido() . "'
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
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable ";
        if ($condicion != "") {
            $consultaResponsable = $consultaResponsable . ' WHERE ' . $condicion;
        }
        $consultaResponsable .= " ORDER BY rapellido ";
        //echo $consultaResponsable;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {

                    $NEmpleado = $row2['rnumeroempleado'];
                    $NLicencia = $row2['rnumerolicencia'];
                    $Nombre = $row2['rnombre'];
                    $Apellido = $row2['rapellido'];

                    $responsable = new ResponsableV();
                    $responsable->cargar($NEmpleado, $NLicencia, $Nombre, $Apellido);
                    array_push($arregloResponsable, $responsable);
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $arregloResponsable;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE responsable SET 
        rnumeroempleado = '" . $this->getRnumeroempleado() . "',
        rnumerolicencia = '" . $this->getRnumerolicencia() . "',
        rnombre = '" . $this->getRnombre() . "',
        rapellido = '" . $this->getRapellido() . "' 
        WHERE rnumeroempleado = " . $this->getRnumeroempleado();

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
            $consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getRnumeroempleado();
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


    public function Buscar($rnumeroempleado)
    {
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable WHERE rnumeroempleado = " . $rnumeroempleado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                if ($row2 = $base->Registro()) {
                    $this->setRnumeroempleado($rnumeroempleado);
                    $this->setRnumerolicencia($row2['rnumerolicencia']);
                    $this->setRnombre($row2['rnombre']);
                    $this->setRapellido($row2['rapellido']);
                    $resp = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }


    public function ultimoId()
    {
        $base = new BaseDatos();
        $consulta = "SELECT MAX(rnumeroempleado) AS rnumeroempleado FROM responsable";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $idResponsable = $row2['rnumeroempleado'];
                }
            }
        }
        return $idResponsable;
    }

    public function __construct()
    {
        $this->setRnumeroempleado("");
        $this->setRnumerolicencia("");
        $this->setRnombre("");
        $this->setRapellido("");

        $this->setMensajeOperacion("");
    }

    public function __toString()
    {
        $string = " Número de Empleado: " . $this->getRnumeroempleado() . "\n";
        $string .= " Número de Licencia: " . $this->getRnumerolicencia() . "\n";
        $string .= " Nombre Responsable: " . $this->getRnombre() . "\n";
        $string .= " Apellido Responsable: " . $this->getRapellido() . "\n";

        return $string;
    }

    /** ###################'Getters & Setters'#################### */

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
     * Get the value of rnumerolicencia
     */
    public function getRnumerolicencia()
    {
        return $this->rnumerolicencia;
    }

    /**
     * Set the value of rnumerolicencia
     *
     * @return  self
     */
    public function setRnumerolicencia($rnumerolicencia)
    {
        $this->rnumerolicencia = $rnumerolicencia;

        return $this;
    }

    /**
     * Get the value of rnombre
     */
    public function getRnombre()
    {
        return $this->rnombre;
    }

    /**
     * Set the value of rnombre
     *
     * @return  self
     */
    public function setRnombre($rnombre)
    {
        $this->rnombre = $rnombre;

        return $this;
    }

    /**
     * Get the value of rapellido
     */
    public function getRapellido()
    {
        return $this->rapellido;
    }

    /**
     * Set the value of rapellido
     *
     * @return  self
     */
    public function setRapellido($rapellido)
    {
        $this->rapellido = $rapellido;

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
}
