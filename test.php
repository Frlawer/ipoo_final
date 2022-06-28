<?php
include_once 'BaseDatos.php';
include_once 'Empresa.php';
include_once 'Viaje.php';
include_once 'ResponsableV.php';
include_once 'Pasajero.php';

// Funcion visual de menu
function menu()
{
    echo "\n" .
        "+================================+\n" .
        "|         MENU PRINCIPAL         |\n" .
        "¦================================¦\n" .
        "| 1. Agregar una empresa         |\n" .
        "| 2. Modificar una empresa       |\n" .
        "| 3. Eliminar una empresa        |\n" .
        "| 4. Ver empresas                |\n" .
        "¦================================¦\n" .
        "| 5. Agregar un viaje            |\n" .
        "| 6. Modificar un viaje          |\n" .
        "| 7. Eliminar un viaje           |\n" .
        "| 8. Ver viaje                   |\n" .
        "¦================================¦\n" .
        "¦================================¦\n" .
        "| 9. Ingresar responsable        |\n" .
        "| 10. Modificar responsable      |\n" .
        "| 11. Eliminar un responsable    |\n" .
        "| 12. Ver responsables           |\n" .
        "¦================================¦\n" .
        "| 13. Ingresar pasajero          |\n" .
        "| 14. Modificar pasajero         |\n" .
        "| 15. Eliminar un pasajero       |\n" .
        "| 16. Ver pasajeros              |\n" .
        "¦================================¦\n" .
        "| 0. Salir                       |\n" .
        "+================================+\n\n";
}

// Functiones Empresa
function ingresarEmpresa()
{
    $empresa = new Empresa();

    $id = readline("(Opcional) Id de la empresa: ");
    $nombre = readline("Nombre de la empresa: ");
    $dir = readline("Direccion de la empresa: ");

    if ($id != null) {
        if (!$empresa->Buscar($id)) {
            $empresa->Cargar($id, $nombre, $dir);

            $respuesta = $empresa->Insertar();
            if ($respuesta == true) {
                echo "\n\t   La empresa fue ingresada en la BD.\n" .
                    "\t========================================\n";
            } else {
                echo $empresa->getMensajeOp();
            }
        } else {
            echo "\nYa existe una empresa con ese ID.\n";
        }
    } else {
        $empresa->setEnombre($nombre);
        $empresa->setEdireccion($dir);

        $respuesta = $empresa->Insertar();
        if ($respuesta == true) {
            echo "\n\t   La empresa fue ingresada en la BD.\n" .
                "\t========================================\n";
        } else {
            echo $empresa->getMensajeOp();
        }
    }
}

function modificarEmpresa()
{
    $empresa = new Empresa();

    $id = readline("Ingrese el id de la empresa a modificar: ");
    $respuesta = $empresa->Buscar($id);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $nuevoid = readline("(Opcional) Id de la empresa: ");
        $nombre = readline("Nombre de la empresa: ");
        $dir = readline("Direccion de la empresa: ");
        if ($nuevoid != "") {
            $empresa->Cargar($nuevoid, $nombre, $dir);
        } else {
            $empresa->setEnombre($nombre);
            $empresa->setEdireccion($dir);
        }
        if (!$empresa->Buscar($nuevoid)) {
            $respuesta = $empresa->Modificar($id);
            if ($respuesta) {
                echo "\n\t   La empresa fue modificada correctamente.\n" .
                    "\t==============================================\n";
            } else {
                echo "\nNo se pudo modificar la empresa.\n";
            }
        } else {
            echo "\nExiste una empresa con ese id.\n";
        }
    } else {
        echo "No se pudo encontrar la empresa con id = " . $id . "\n";
    }
}

function eliminarEmpresa()
{
    $empresa = new Empresa();

    $id = readline("Ingrese el id de la empresa a eliminar: ");
    $respuesta = $empresa->Buscar($id);

    if ($respuesta) {
        $respuesta = $empresa->Eliminar();
        if ($respuesta) {
            echo "\n\t   La empresa fue eliminada de la BD.\n" .
                "\t=========================================\n";
        } else {
            echo "\nNo se pudo eliminar la empresa.\n";
        }
    } else {
        echo "No se pudo encontrar la empresa con id = " . $id . ".\n";
    }
}

function mostrarEmpresa()
{
    $empresa = new Empresa();

    $resp = readline("Mostrar todas las empresas? (s/n) ");

    if ($resp == 's') {
        $colEmpresas = $empresa->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colEmpresas as $empresa) {

            echo $empresa;
            echo "-------------------------------------------------------";
        }
    } else {
        $id = readline("Ingrese el id de la empresa: ");
        if (is_numeric($id)) {
            $respuesta = $empresa->Buscar($id);
            if ($respuesta) {
                echo $empresa;
            } else {
                echo "No se pudo encontrar la empresa.";
            }
        } else {
            echo "ID ingresado no es valido.\n";
        }
    }
}
// Todo funciona hasta aca.

//Funciones Viaje
function ingresarViaje()
{
    $viaje = new Viaje();
    $empresa = new Empresa();
    $responsable = new ResponsableV();

    $id = readline("(Opcional) Id del viaje: ");
    $destino = readline("Destino del viaje: ");
    $cantmax = readline("Cantidad maxima de pasajeros: ");
    $idempresa = readline("ID de la empresa a cargo: ");
    $nempleado = readline("Numero de empleado responsable: ");
    $importe = readline("Importe: ");
    $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
    $idayvuelta = readline("Ida y vuelta? ");

    if ($id != null) {
        if (!$viaje->Buscar($id)) {
            if ($empresa->Buscar($idempresa) && $responsable->Buscar($nempleado)) {
                $viaje->Cargar($id, $destino, $cantmax, $idempresa, $nempleado, $importe, $tipoAsiento, $idayvuelta);

                $respuesta = $viaje->Insertar();
                if ($respuesta == true) {
                    echo "\n\t   El viaje fue ingresado en la BD.\n" .
                        "\t======================================\n";
                } else {
                    echo $viaje->getMensajeOp();
                }
            } else {
                echo "\nNo existe la empresa o responsable a cargo.\n";
            }
        } else {
            echo "\nYa existe un viaje con ese ID.\n";
        }
    } else {
        if ($empresa->Buscar($idempresa) && $responsable->Buscar($nempleado)) {
            $viaje->setDestino($destino);
            $viaje->setCantMaxPasajeros($cantmax);
            $viaje->setIdEmpresa($idempresa);
            $viaje->setNumeroEmpleado($nempleado);
            $viaje->setImporte($importe);
            $viaje->setTipoAsiento($tipoAsiento);
            $viaje->setIdaYVuelta($idayvuelta);

            $respuesta = $viaje->Insertar();
            if ($respuesta == true) {
                echo "\n\t   El viaje fue ingresado en la BD.\n" .
                    "\t======================================\n";
            } else {
                echo $viaje->getMensajeOp();
            }
        } else {
            echo "\nNo existe la empresa o responsable a cargo.\n";
        }
    }
}

function modificarViaje()
{
    $viaje = new Viaje();

    $id = readline("Ingrese el id del viaje a modificar: ");
    $respuesta = $viaje->Buscar($id);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $nuevoid = readline("(Opcional) Id del viaje: ");
        $destino = readline("Destino del viaje: ");
        $cantmax = readline("Cantidad maxima de pasajeros: ");
        $idempresa = readline("ID de la empresa a cargo: ");
        $nempleado = readline("Numero de empleado responsable: ");
        $importe = readline("Importe: ");
        $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
        $idayvuelta = readline("Ida y vuelta? ");
        if ($nuevoid != "") {
            $viaje->Cargar($nuevoid, $destino, $cantmax, $idempresa, $nempleado, $importe, $tipoAsiento, $idayvuelta);

            $respuesta = $viaje->Insertar();
            // Actualizando los pasajeros que estan asociados a ese id de viaje.
            $pasajeros = new Pasajero();

            $pasajeros->Modificar("", "UPDATE pasajero SET idviaje = " . $nuevoid . " WHERE idviaje = " . $id);
            $respuesta = $viaje->Buscar($id);
            $respuesta = $viaje->Eliminar();
            if ($respuesta) {
                echo "\n\t   El viaje fue modificado correctamente.\n" .
                    "\t============================================\n";
            } else {
                echo "\nNo se pudo crear el nuevo viaje.\n";
            }
        } else {
            $viaje->setDestino($destino);
            $viaje->setCantMaxPasajeros($cantmax);
            $viaje->setIdEmpresa($idempresa);
            $viaje->setNumeroEmpleado($nempleado);
            $viaje->setImporte($importe);
            $viaje->setTipoAsiento($tipoAsiento);
            $viaje->setIdaYVuelta($idayvuelta);
        }
    } else {
        echo "No se pudo encontrar el viaje con id = " . $id . ".\n";
    }
}

function eliminarViaje()
{
    $viaje = new Viaje();

    $id = readline("Ingrese el id del viaje a eliminar: ");
    $respuesta = $viaje->Buscar($id);

    if ($respuesta) {
        $respuesta = $viaje->Eliminar();
        if ($respuesta) {
            echo "\n\t   El viaje fue eliminado de la BD.\n" .
                "\t=========================================\n";
        } else {
            echo "\nNo se pudo eliminar el viaje.\n";
        }
    } else {
        echo "No se pudo encontrar el viaje con id = " . $id . ".\n";
    }
}

function mostrarViaje()
{
    $viaje = new Viaje();

    $resp = readline("Mostrar todos los viajes? (s/n) ");

    if ($resp == 's') {
        $colViajes = $viaje->Listar("");
        foreach ($colViajes as $viaje) {

            echo $viaje;
            echo "\n-------------------------------------------------------\n";
        }
    } else {
        $id = readline("Ingrese el id del viaje: ");
        if (is_numeric($id)) {
            $respuesta = $viaje->Buscar($id);
            if ($respuesta) {
                echo $viaje;
            } else {
                echo "No se pudo encontrar el viaje.";
            }
        } else {
            echo "ID ingresado no es valido.\n";
        }
    }
}

// Funciones Creacion/Modificacion de Responsables y Pasajeros.
// Responsables
function ingresarResponsable()
{
    $responsable = new ResponsableV();

    $id = readline("(Opcional) Numero de empleado: ");
    $numLic = readline("Numero de licencia: ");
    $nombre = readline("Nombre del responsable: ");
    $apellido = readline("Apellido del responsable: ");

    if ($id != null) {
        if (!$responsable->Buscar($id)) {
            $responsable->Cargar($id, $numLic, $nombre, $apellido);

            $respuesta = $responsable->Insertar();
            if ($respuesta) {
                echo "\n\t   El responsable fue ingresada en la BD.\n" .
                    "\t========================================\n";
            } else {
                echo $responsable->getMensajeOp();
            }
        } else {
            echo "\nYa existe un responsable con ese ID.\n";
        }
    } else {
        $responsable->setLicencia($numLic);
        $responsable->setNombre($nombre);
        $responsable->setApellido($apellido);

        $respuesta = $responsable->Insertar();
        if ($respuesta) {
            echo "\n\t   El responsable fue ingresada en la BD.\n" .
                "\t========================================\n";
        } else {
            echo $responsable->getMensajeOp();
        }
    }
}

function modificarResponsable()
{
    $responsable = new ResponsableV();

    $numE = readline("Ingrese el numero de empleado del responsable a modificar: ");
    $respuesta = $responsable->Buscar($numE);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $nuevoNumE = readline("(Opcional) Numero de empleado: ");
        $numLic = readline("Numero de licencia: ");
        $nombre = readline("Nombre del responsable: ");
        $apellido = readline("Apellido del responsable: ");
        if ($nuevoNumE != null) {
            $responsable->Cargar($nuevoNumE, $numLic, $nombre, $apellido);
        } else {
            $responsable->setLicencia($numLic);
            $responsable->setNombre($nombre);
            $responsable->setApellido($apellido);
        }

        $respuesta = $responsable->Modificar($numE);
        if ($respuesta) {
            echo "\n\t   El responsable fue modificada correctamente.\n" .
                "\t==============================================\n";
        } else {
            echo "\nNo se pudo modificar el responsable.\n";
        }
    } else {
        echo "No se pudo encontrar el responsable con numero de empleado: " . $numE . "\n";
    }
}

function eliminarResponsable()
{
    $responsable = new ResponsableV();

    $numE = readline("Ingrese el numero de empleado del responsable a eliminar: ");
    $respuesta = $responsable->Buscar($numE);

    if ($respuesta) {
        $respuesta = $responsable->Eliminar();
        if ($respuesta) {
            echo "\n\t   El responsable fue eliminado de la BD.\n" .
                "\t=========================================\n";
        } else {
            echo "\nNo se pudo eliminar el responsable.\n";
        }
    } else {
        echo "No se pudo encontrar el responsable con numero de empleado: " . $numE . ".\n";
    }
}

function mostrarResponsable()
{
    $responsable = new ResponsableV();

    $resp = readline("Mostrar todos los responsables? (s/n) ");

    if ($resp == 's') {
        $colResponsables = $responsable->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colResponsables as $responsable) {

            echo $responsable;
            echo "-------------------------------------------------------";
        }
    } else {
        $numE = readline("Ingrese el numero de empleado: ");
        if (is_numeric($numE)) {
            $respuesta = $responsable->Buscar($numE);
            if ($respuesta) {
                echo $responsable;
            } else {
                echo "No se pudo encontrar el responsable.";
            }
        } else {
            echo "El valor ingresado no es valido.\n";
        }
    }
}

// Pasajeros
function ingresarPasajero()
{
    $pasajero = new Pasajero();
    $viaje = new Viaje();

    $dni = readline("Documento de pasajero: ");
    $nombre = readline("Nombre: ");
    $apellido = readline("Apellido: ");
    $telefono = readline("Telefono: ");
    $idviaje = readline("Id del viaje: ");

    if ($viaje->Buscar($idviaje)) {
        if (!$pasajero->Buscar($dni)) {
            $pasajero->Cargar($nombre, $apellido, $dni, $telefono, $idviaje);

            $respuesta = $pasajero->Insertar();
            if ($respuesta == true) {
                echo "\n\t   La pasajero fue ingresado en la BD.\n" .
                    "\t========================================\n";
            } else {
                echo $pasajero->getMensajeOp();
            }
        } else {
            echo "\nYa existe un pasajero con ese documento.\n";
        }
    } else {
        echo "El viaje no existe.\n";
    }
}

function modificarPasajero()
{
    $pasajero = new Pasajero();
    $viaje = new Viaje();

    $dni = readline("Ingrese el documento del pasajero a modificar: ");
    if (is_numeric($dni)) {
        $respuesta = $pasajero->Buscar($dni);
        if ($respuesta) {
            echo "Ingrese los nuevos datos.\n";
            $nuevodni = readline("Documento de pasajero: ");
            $nombre = readline("Nombre: ");
            $apellido = readline("Apellido: ");
            $telefono = readline("Telefono: ");
            $idviaje = readline("Id del viaje: ");

            if ($viaje->Buscar($idviaje)) {
                if ($nuevodni != null) {
                    if (!$pasajero->Buscar($nuevodni)) {
                        $pasajero->Cargar($nombre, $apellido, $nuevodni, $telefono, $idviaje);
                    } else {
                        echo "Ya existe un pasajero con ese documento.\n";
                    }
                } else {
                    $pasajero->setNombre($nombre);
                    $pasajero->setApellido($apellido);
                    $pasajero->setTelefono($telefono);
                    $pasajero->setIdViaje($idviaje);
                }

                $respuesta = $pasajero->Modificar($dni);
                if ($respuesta) {
                    echo "\n\t   El pasajero fue modificado correctamente.\n" .
                        "\t==============================================\n";
                } else {
                    echo "\nNo se pudo modificar el pasajero.\n";
                }
            } else {
                echo "El viaje no existe.\n";
                echo "\nNo se pudo modificar el pasajero.\n";
            }
        } else {
            echo "No se pudo encontrar el pasajero de documento: " . $dni . ".\n";
        }
    } else {
        echo "El documento ingresado es incorrecto.\n";
    }
}

function eliminarPasajero()
{
    $pasajero = new Pasajero();

    $dni = readline("Ingrese el documento del pasajero a eliminar: ");

    if (is_numeric($dni)) {
        $respuesta = $pasajero->Buscar($dni);

        if ($respuesta) {
            $respuesta = $pasajero->Eliminar();
            if ($respuesta) {
                echo "\n\t   El pasajero fue eliminado de la BD.\n" .
                    "\t=========================================\n";
            } else {
                echo "\nNo se pudo eliminar el pasajero.\n";
            }
        } else {
            echo "No se pudo encontrar el pasajero el documento: " . $dni . ".\n";
        }
    } else {
        echo "El documento ingresado es incorrecto.\n";
    }
}

function mostrarPasajero()
{
    $pasajero = new Pasajero();

    $resp = readline("Mostrar todos los pasajeros? (s/n) ");

    if ($resp == 's') {
        $colPasajeros = $pasajero->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colPasajeros as $pasajero) {

            echo $pasajero;
            echo "-------------------------------------------------------";
        }
    } else {
        $numE = readline("Ingrese documento del pasajero: ");
        if (is_numeric($numE)) {
            $respuesta = $pasajero->Buscar($numE);
            if ($respuesta) {
                echo $pasajero;
            } else {
                echo "No se pudo encontrar el pasajero.";
            }
        } else {
            echo "El documetno ingresado no es valido.\n";
        }
    }
}

// Switch de opciones para guiar al usuario con cada funcion.
function opciones()
{
    menu();
    $resp = false;

    $opcion = readline("Elija una opcion: ");
    switch ($opcion) {
        case 0:
            $resp = false;
            break;
        case 1:
            ingresarEmpresa();
            break;
        case 2:
            modificarEmpresa();
            break;
        case 3:
            eliminarEmpresa();
            break;
        case 4:
            mostrarEmpresa();
            break;
        case 5:
            ingresarViaje();
            break;
        case 6:
            modificarViaje();
            break;
        case 7:
            eliminarViaje();
            break;
        case 8:
            mostrarViaje();
            break;
        case 9:
            ingresarResponsable();
            break;
        case 10:
            modificarResponsable();
            break;
        case 11:
            eliminarResponsable();
            break;
        case 12:
            mostrarResponsable();
            break;
        case 13:
            ingresarPasajero();
            break;
        case 14:
            modificarPasajero();
            break;
        case 15:
            eliminarPasajero();
            break;
        case 16:
            mostrarPasajero();
            break;
        default:
            echo "Opcion incorrecta. Tiene que ser un numero entre 1 y 16.";
            time_nanosleep(2, 0);
            $resp = true;
    }

    echo "\n";
    if ($opcion != 0 && ($opcion > 0 && $opcion <= 16)) {
        $resp = 's' == readline("Realizar otra operacion? (s/n) ");
    }

    return $resp;
}

// While loop para seleccionar otra opcion si el usuario lo desea.
$resp = true;
while ($resp) {
    $resp = opciones();
}

echo "===========================" .
    "\n\tSaliendo...\n" .
    "===========================";
