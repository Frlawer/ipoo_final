<?php
include_once "ResponsableV.php";
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "Pasajero.php";
include_once "Viaje.php";


/**************************************/
/***** DATOS DE LOS INTEGRANTES *******/
/**************************************/

/* ... Rodriguez Francisco. FAI-1094. TDW. francisco.rodriguez@est.fi.uncoma.edu.ar. https://github.com/frlawer/ ... */

/**************************************/
/***** DEFINICION DE FUNCIONES ********/
/**************************************/

function menuInicial()
{
    $menu = [
        "Ingresar Viaje",
        "Modificar Viaje",
        "Eliminar Viaje",
        "Mostrar datos del Viaje",
        "Administrar pasajeros",
        "X Salir"
    ];
    // imprimo el menu con bucle
    echo "Selecciona una opción del Menú: \n";
    foreach ($menu as $key => $value) {
        echo $key + 1 . ") " . $value . "\n";
    }
    // llamo a la funcion solicitarNumero() para solicitar un numero y lo retorno.
    $opcion = solicitarNumero(1, 6);
    return $opcion;
}

function menuPasajero()
{
    $menu = [
        "Ingresar Pasajero",
        "Modificar Pasajero",
        "Eliminar Pasajero",
        "Mostrar Pasajeros del Viaje",
        "<-- Regresar"
    ];
    // imprimo el menu con bucle
    echo "Selecciona una opción del Menú: \n";
    foreach ($menu as $key => $value) {
        echo $key + 1 . ") " . $value . "\n";
    }
    // llamo a la funcion solicitarNumero() para solicitar un numero y lo retorno.
    $opcion = solicitarNumero(1, 5);
    return $opcion;
}
function solicitarNumero($min, $max)
{
    $numero = trim(fgets(STDIN));
    while (!is_int($numero) && !($numero >= $min && $numero <= $max)) {
        echo "Debe ingresar un número entre " . $min . " y " . $max . ": \n";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}

function iniciarEmpresa($empresa)
{

    $datos = [];
    echo "Ingrese Nombre de la Empresa: \n";
    $datos["enombre"] = trim(fgets(STDIN));
    echo "Ingrese Dirección de la Empresa: \n";
    $datos["edireccion"] = trim(fgets(STDIN));

    $empresa->cargar(null, $datos["enombre"], $datos["edireccion"]);
    $empresa->insertar();
    $empresa->setIdempresa($empresa->ultimoId());

    echo "\n...Empresa iniciada... \n";
    return $datos;
}

function iniciarResponsable($responsable)
{
    // muestro menu
    echo "Seleccionar una opción:\n";
    echo "1) Seleccionar responsable.\n";
    echo "2) Ingresar responsable.\n";

    $opcion = solicitarNumero(1, 2);

    //si opcion 1 
    if ($opcion == 1) {

        //solicito numero de empleado, lo busco
        echo "Ingrese su numero de empleado.\n";
        $nempleado = trim(fgets(STDIN));
        $responsableBD = $responsable->Buscar($nempleado);

        while (!$responsableBD) {

            echo "El responsable con ese numero no existe.\n";
            echo "...Intente de nuevo... \n";
            $nempleado = trim(fgets(STDIN));
            $responsableBD = $responsable->Buscar($nempleado);
        }
        echo "\n...Responsable seleccionado... \n";
    } else {
        $datos = cargarResponsable();

        $existeResponsable = $responsable->listar("rnumerolicencia = " . $datos["rnumerolicencia"]);

        if (empty($existeResponsable)) {

            $responsable->cargar(null, $datos["rnumerolicencia"], $datos["rnombre"], $datos["rapellido"]);
            if ($responsable->insertar()) {

                $responsable->setRnumeroempleado($responsable->ultimoId());
                echo "\n...Empleado ingresado...\n";
            } else {
                echo $responsable->getMensajeoperacion();
            }
        } else {

            echo "\n...El empleado ingresado ya existe y fue seleccionado... \n";
            $responsable->Buscar($existeResponsable[0]->getRnumeroempleado());
        }
    }

    return $responsable;
}

function cargarResponsable()
{
    $datosResponsable = [];
    echo "Ingrese número de licencia: \n";
    $datosResponsable["rnumerolicencia"] = trim(fgets(STDIN));
    echo "Ingrese nombre del empleado: \n";
    $datosResponsable["rnombre"] = trim(fgets(STDIN));
    echo "Ingrese apellido del empleado: \n";
    $datosResponsable["rapellido"] = trim(fgets(STDIN));

    return $datosResponsable;
}

function datosViaje()
{
    $datos = [];
    echo "Ingrese destino: \n";
    $datos["vdestino"] = trim(fgets(STDIN));
    echo "Capacidad maxima de pasajeros: \n";
    $datos["vcantmaxpasajeros"] = trim(fgets(STDIN));
    echo "Ingrese Importe del Viaje: \n";
    $datos["vimporte"] = trim(fgets(STDIN));
    echo "Seleccione tipo de asiento: \n1) Ejecutivo \n2) Estandard\n";
    $datos["tipoAsiento"] = trim(fgets(STDIN));
    echo "1) Ida \n2) Ida y vuelta: \n";
    $datos["idayvuelta"] = trim(fgets(STDIN));

    return $datos;
}

/**
 * datosPasajero
 * Solicita los datos de pasajero y retorna los mismos
 * @return array
 */
function datosPasajero()
{
    $datosPasajero = [];
    echo "Ingrese DNI: \n";
    $datosPasajero["rdocumento"] = trim(fgets(STDIN));
    echo "Ingrese Nombre: \n";
    $datosPasajero["pnombre"] = trim(fgets(STDIN));
    echo "Ingrese Apellido: \n";
    $datosPasajero["papellido"] = trim(fgets(STDIN));
    echo "Ingrese Teléfono: \n";
    $datosPasajero["ptelefono"] = trim(fgets(STDIN));

    return $datosPasajero;
}

function separador($txt = "")
{
    $separador = "\n---------------" . $txt . "---------------\n";
    return $separador;
}
/**************************************/
/*********** PROGRAMA PRINCIPAL *******/
/**************************************/


//Proceso:
$empresa = new Empresa();
$responsable = new ResponsableV();
$viaje = new Viaje();
$pasajero = new Pasajero();
echo "\nIniciando...\n";
echo separador("Cargar Datos Empresa:");
iniciarEmpresa($empresa);
echo separador("Responsable:");
iniciarResponsable($responsable);

do {

    echo "\n----------VIAJE----------\n";
    $opcion = menuInicial();

    switch ($opcion) {
        case 1:
            echo separador("Cargar datos Viaje");
            $datos = datosViaje();
            $viaje->cargar(null, $datos['vdestino'], $datos['vcantmaxpasajeros'], null, $empresa, $responsable, $datos['vimporte'], $datos['tipoAsiento'], $datos['idayvuelta']);

            if ($viaje->insertar()) {

                echo "Viaje ingresado con éxito: \n";
            } else {

                $viaje->getMensajeOperacion();
            }
            break;
        case 2:
            //Modificar Viaje
            echo separador();
            echo "Ingrese el ID del viaje a modificar: \n";
            $idViaje = trim(fgets(STDIN));
            $viajeListado = $viaje->Buscar($idViaje);

            if ($viajeListado) {

                $datos = datosViaje();
                $viaje->cargar($idViaje, $datos['vdestino'], $datos['vcantmaxpasajeros'], null, $empresa, $responsable, $datos['vimporte'], $datos['tipoAsiento'], $datos['idayvuelta']);

                if ($viaje->modificar($idViaje)) {
                    echo "\n...Viaje Modificado...\n";
                } else {
                    echo $viaje->getMensajeOperacion();
                }
            } else {

                echo "El viaje ingresado no existe.\n";
            }

            break;
        case 3:
            //Eliminar Viaje
            echo "Ingrese el ID del viaje a Eliminar: \n";
            $idViaje = trim(fgets(STDIN));
            $viajeListado = $viaje->Buscar($idViaje);
            //si existe
            if ($viajeListado) {
                //verificar si tiene pasajeros
                $pasajeros = $pasajero->listar("idviaje = " . $viaje->getIdviaje());
                if (!$pasajeros) {
                    if ($viaje->eliminar()) {

                        echo "Viaje eliminado...!! \n";
                    } else {
                        echo $viaje->getMensajeOperacion();
                    }
                } else {
                    echo "El viaje posee asignados pasajeros. \n¿Desea eliminarlos del viaje antes? S/N\n";
                    $resp = trim(fgets(STDIN));
                    //si eliminar elimino
                    if (strtoupper($resp) == "S") {
                        for ($i = 0; $i < count($pasajeros); $i++) {
                            $pasajeros[$i]->eliminar();
                        }
                        echo "Pasajeros eliminados\n";
                        if ($viaje->eliminar()) {

                            echo "Viaje eliminado...!! \n";
                        } else {
                            echo $viaje->getMensajeOperacion();
                        }
                    }
                }
            } else {
                echo "El viaje ingresado no existe. \n";
            }
            break;
        case 4:
            //Mostrar Viaje
            echo "Ingrese el ID del viaje a mostrar: \n";
            $idViaje = trim(fgets(STDIN));
            $viajeListado = $viaje->Buscar($idViaje);

            if ($viajeListado) {
                echo separador("Mostrar Viaje");
                echo $viaje;
                $listaPasajeros = $pasajero->listar("idviaje = " . $viaje->getIdviaje());
                if (!$listaPasajeros) {
                    echo "...El viaje no posee pasajeros...";
                } else {
                    foreach ($listaPasajeros as $pasajero) {
                        echo "----Pasajero----\n";
                        echo "DNI: " . $pasajero->getRdocumento() . "\n";
                        echo "Nombre: " . $pasajero->getPnombre() . "\n";
                        echo "Apellido: " . $pasajero->getPapellido() . "\n";
                        echo "Teléfono: " . $pasajero->getPtelefono() . "\n";
                    }
                }
            } else {
                echo "El viaje ingresado no existe. \n";
            }

            break;
        case 5:
            do {
                //llamo menu Pasajero
                echo "----------PASAJERO----------\n";
                $opcionPasajero = menuPasajero();
                switch ($opcionPasajero) {
                    case 1:

                        $datos = datosPasajero();
                        $existePasajero = $pasajero->listar("rdocumento = " . $datos['rdocumento']);

                        if (!$existePasajero) {

                            echo "Seleccione el viaje al que desea agregarlo. \n";
                            $viajes = $viaje->listar();
                            foreach ($viajes as $viaje) {
                                echo "ID: " . $viaje->getIdViaje() . " - Destino: " . $viaje->getVdestino() . "\n";
                            }
                            $resp = trim(fgets(STDIN));
                            if ($viaje->Buscar($resp)) {
                                $pasajero->cargar($datos['rdocumento'], $datos['pnombre'], $datos['papellido'], $datos['ptelefono'], $viaje);

                                if ($pasajero->insertar()) {

                                    echo "...Pasajero ingresado con éxito...\n";
                                } else {

                                    echo $pasajero->getMensajeoperacion();
                                }
                            } else {
                                echo "El viaje no existe";
                            }
                        } else {
                            echo "...El pasajero con ese DNI ya existe...\n";
                        }

                        break;
                    case 2:
                        //solicito id del pasajero para buscarlo en la BD
                        echo "Ingrese el DNI del pasajero a modificar: \n";
                        $pasajeros = $pasajero->listar();
                        foreach ($pasajeros as $pasajero) {

                            echo separador();
                            echo "DNI: " . $pasajero->getRdocumento() . " - Nombre Completo: " . $pasajero->getPapellido() . " " . $pasajero->getPnombre() . "\n";
                            echo separador();
                        }

                        $idpasajero = trim(fgets(STDIN));
                        $pasajeroListado = $pasajero->Buscar($idpasajero);
                        //si existe solicito edicion del pasajero y lo envio a modificar en al BD
                        if ($pasajeroListado) {

                            $datos = datospasajero();

                            echo "Seleccione el ID del viaje al que desea agregarlo. \n";
                            $viajes = $viaje->listar();
                            foreach ($viajes as $viaje) {
                                echo "ID: " . $viaje->getIdViaje() . " - Destino: " . $viaje->getVdestino() . "\n";
                            }
                            $resp = trim(fgets(STDIN));
                            if ($viaje->Buscar($resp)) {
                                $pasajero->cargar($datos['rdocumento'], $datos['pnombre'], $datos['papellido'], $datos['ptelefono'], $viaje);

                                if ($pasajero->modificar($idpasajero)) {

                                    echo "...Pasajero modificado con éxito...\n";
                                } else {

                                    echo $pasajero->getMensajeoperacion();
                                }
                            } else {
                                echo "El viaje no existe";
                            }
                        } else {

                            echo "El pasajero ingresado no existe.\n";
                        }

                        break;
                    case 3:

                        echo "Ingrese el DNI del pasajero a Eliminar: \n";
                        $idpasajero = trim(fgets(STDIN));
                        $pasajeroListado = $pasajero->Buscar($idpasajero);

                        if ($pasajeroListado) {
                            if ($pasajero->eliminar()) {

                                echo "pasajero eliminado...!! \n";
                            } else {
                                echo $pasajero->getMensajeOperacion();
                            }
                        } else {
                            echo "El pasajero ingresado no existe. \n";
                        }
                        break;
                    case 4:

                        echo "Ingrese el DNI del pasajero a mostrar: \n";
                        $idpasajero = trim(fgets(STDIN));
                        $pasajeroListado = $pasajero->Buscar($idpasajero);

                        if ($pasajeroListado) {

                            echo separador();
                            echo $pasajero;
                            echo separador();
                        } else {
                            echo "El pasajero ingresado no existe. \n";
                        }

                        break;
                    case 5:
                        echo "Regresando al menú anterior...\n";
                        break;
                }
            } while ($opcionPasajero != 5);
            break;
    }
} while ($opcion != 6);
