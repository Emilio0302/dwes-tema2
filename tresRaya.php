<?php

$t = [
    [" ", " ", " "],
    [" ", " ", " "],
    [" ", " ", " "]
];

//Esta funcion se encarga de dibujar el tablero del tres en raya
function pintarTablero(array $t): void
{
    echo "+-----+-----+-----+\n" .
        "|  " . $t[0][0] . "  |  " . $t[0][1] . "  |  " . $t[0][2] . "  |\n" .
        "+-----+-----+-----+\n" .
        "|  " . $t[1][0] . "  |  " . $t[1][1] . "  |  " . $t[1][2] . "  |\n" .
        "+-----+-----+-----+\n" .
        "|  " . $t[2][0] . "  |  " . $t[2][1] . "  |  " . $t[2][2] . "  |\n" .
        "+-----+-----+-----+\n";
}

//Esta función se encarga de reiniciar el tablero cuando la partida se acabe
function reiniciarTablero(array &$t): void
{
    for ($i = 0; $i < count($t); $i++) {
        for ($j = 0; $j < count($t[$i]); $j++) {
            $t[$i][$j] = " ";
        }
    }
}
//Esta funcion comprueba si hay un espacio libre
function espacioLibre(array $t, $x, $y): bool
{
    return $t[$x][$y] == " ";
}
/* Esta funcion se encarga de comprobar que los numeros que se introduzcan sean los correctos 
y y si hay una ficha que no se pueda sobreescribir*/
function comprobarPosicion(array $t, $x, $y): bool
{
    return is_numeric($x) && is_numeric($y) && $x > 0 && $x < 4 && $y > 0 && $y < 4 && espacioLibre($t, $x - 1, $y - 1); 
}

//En esta funcion le asignamos valores a x y a y
function asignarValores(array &$t, int $x, int $y, string $valor): void
{
    $t[$x][$y] = $valor;
}

function movimientoGanador(array $t, $l): bool
{
    /*Esta funcion se encargar de devolver quien ha hecho el tres en raya
      return true -> El parametro l ha ganado 
      return false -> No hay ganador*/
    if (
        //Vertical
        ($t[1][1] == $l && $t[1][1] == $t[0][1] && $t[1][1] == $t[2][1]) ||
        //Horizontal
        ($t[1][1] == $l && $t[1][1] == $t[1][0] && $t[1][1] == $t[1][2]) ||
        //Diagonal arriba-izquierda a abajo-derecha
        ($t[1][1] == $l && $t[1][1] == $t[0][0] && $t[1][1] == $t[2][2]) ||
        //Diagonal arriba-derecha a abajo-izquierda
        ($t[1][1] == $l && $t[1][1] == $t[0][2] && $t[1][1] == $t[2][0]) ||
        //Horizontal-arriba
        ($t[0][0] == $l && $t[0][0] == $t[0][1] && $t[0][0] == $t[0][2]) ||
        //Vertical-izquierda
        ($t[0][0] == $l && $t[0][0] == $t[1][0] && $t[0][0] == $t[2][0]) ||
        //Horizontal-abajo
        ($t[2][2] == $l && $t[2][2] == $t[2][1] && $t[2][2] == $t[2][0]) ||
        //Vertical-derecha
        ($t[2][2] == $l && $t[2][2] == $t[1][2] && $t[2][2] == $t[0][2])
    ) {

        return true;
    } else {
        return false;
    }
}
//Esta funcion se encarga de comprobar si la matriz esta llena
function tableroLleno(array $t): bool
{
    for ($i = 0; $i < count($t); $i++) {
        for ($j = 0; $j < count($t[$i]); $j++) {
            if ($t[$i][$j] == " ") {
                return false;
            }
        }
    }
    return true;
}
//Esta funcion se encarga de avanzar el turno
function siguienteTuro(string $turno): string
{
    return $turno == "x" ? "o" : "x";
}
//bucle de partida
do {
    //Al principio de cada partida el turno sera de x
    $turno = "x";
    //bucle de turnos
    do {
        echo "turno de " . $turno . "\n";
        pintarTablero($t);

        //se piden coordenadas
        do {
            echo "Introduce las filas y columnas con un espacio entre las dos ";
            fscanf(STDIN, "%d %d", $x, $y);
        } while (!comprobarPosicion($t, $x, $y) );
        $x--;
        $y--;

        //asignar valores
        asignarValores($t, $x, $y, $turno);

        //comprobar si hay 3 en raya
        $ganador = movimientoGanador($t, $turno);

        /*si sale false, tenemos que comprobar si es un empate
        será empate si despues de comprobar que no hay ganador
        el tablero está lleno*/
        if (!$ganador) {
            $empate = tableroLleno($t);
            $turno = siguienteTuro($turno);
        }
    } while (!$ganador && !$empate); // mientras no haya ganador o haya empate
    pintarTablero($t);
    //decimos resultado
    echo $ganador ? "ganan: " . $turno . "\n" : "empate\n";

    //reinicio
    reiniciarTablero($t);

    //preguntamos por otra partida
    do {
        echo "otra partida? si / no\n";
        fscanf(STDIN, "%s", $otra);
    } while ($otra != "si" && $otra != "no");
} while ($otra == "si");
