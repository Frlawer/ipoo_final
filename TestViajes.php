<?php
include_once "Responsable.php";
include_once "BaseDatos.php";
include_once "Pasajero.php";
include_once "Viaje.php";

$responsable = new ResponsableV();

$responsable->cargar(1, 9, "Francisco", "Rodriguez");
$responsable->insertar();

$pasajero1 = new Pasajero();
$pasajero1->cargar(32282931, "Felipe", "Suarez", 2995135074, 1);
$pasajero1->insertar();
$pasajero2 = new Pasajero();
$pasajero2->cargar(32134923, "Jose", "Marcoli", 299512424, 1);
$pasajero2->insertar();
$pasajero3 = new Pasajero();
$pasajero3->cargar(32282931, "Pamela", "Ramirez", 2992435455, 1);
$pasajero3->insertar();
