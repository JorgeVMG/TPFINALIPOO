<?php
include_once "../baseDatos/BaseDatos.php";
include_once "../viaje/empresa.php";
include_once "../viaje/responsable.php";
include_once "../viaje/viaje.php";
include_once "../viaje/pasajero.php";
include_once "../viaje/pasajeroEstandar.php";
include_once "../viaje/pasajeroVip.php";
include_once "../viaje/pasajeroNecesidades.php";
$empresa = new Empresa();
$responsable = new Responsable();
$viaje = new Viaje();
$pasajeroEstandar = new PasajeroEstandar();
$pasajeroVip = new PasajeroVip();
$pasajeroNecesidades = new PasajeroNecesidades();
echo "Hola, que desea hacer?\n";
do{
    echo "-----------------------------\n";
    echo "(1)Ingresar o Cargar Informarcion\n(2)Modificar Informacion\n(3)Elimanar Informacion\n(4)Presentar Informacion\n(5)Salir\nOpcion: ";
    $dese = trim(fgets(STDIN));
    switch($dese){
        case 1:
            echo "¿Que desea ingresar o cargar?\n(1)Empresa(2)Viaje(3)pasajero\nOpcion: ";
            $opcion2 = trim(fgets(STDIN));
            if($opcion2 == 1){
                echo "(1)Cargar Una Empresa\n(2)Ingresar una nueva empresa a la base de datos\nOpcion: ";
                $opcion3 = trim(fgets(STDIN));
                if($opcion3 == 1){
                    // cargar empresa
                    $colEmpre= $empresa->listar();
                    if(count($colEmpre)>0){
                        echo "Empresas Cargadas\n";
                        foreach($colEmpre as $empre){
                            echo $empre;
                            echo "-----------------------------\n";
                        }
                    }
                    echo "Ingrese el Id de la empresa que desea ingresar: ";
                    $id = trim(fgets(STDIN));
                    if($empresa->Buscar($id)){
                        echo "empresa cargada";
                        echo $empresa;
                    }else{
                        echo "no se pudo cargar la empresa".$empresa->getMensajeOperacion();
                    }
                }else{
                    //ingresar empresa
                    echo "ingresar el nombre de la empresa: \n";
                    $nomEmpresa = trim(fgets(STDIN));
                    echo "ingresar la ubicacion de la empres\n";
                    $ubicacion = trim(fgets(STDIN));
                    $empresa->cargar($nomEmpresa, $ubicacion);
                    if($empresa->Insertar()){
                        echo "se inserto correctamente la empresa\n";
                        echo $empresa;
                    }else{
                        echo "no se pudo insetar la empresa ".$empresa->getMensajeOperacion();
                    }
                }
            }elseif($opcion2 == 2){
                echo "(1)Cargar Un Viaje\n(2)Ingresar un nuevo Viaje a la base de datos\nOpcion: ";
                $opcion3 = trim(fgets(STDIN));
                if($opcion3 == 1){
                    $colEm = $empresa->listar();
                    $novi = 0;
                    if(count($colEm)>0){
                        foreach ($colEm as $emp){
                            $colV = $viaje->listar($emp);
                            if(count($colV)>0){
                                foreach ($colV as $viaj) {
                                    echo "Id de viaje : " . $viaj->getIdViaje()."\n";
                                }
                            }else{
                                $novi++;
                            }
                        }
                    }
                    if($novi != count($colEm)){
                        echo "Ingrese el codigo del viaje que desea ingresar: ";
                        $codigo = trim(fgets(STDIN));
                        if($viaje->Buscar($codigo)){
                            echo "viaje cargado\n";
                        }else{
                            echo "no se pudo cargar el viaje".$empresa->getMensajeOperacion()."\n";
                        }
                    }else{
                        echo "no hay viajes cargados\n";
                    }
                }else{
                    if($empresa->getIdempresa()==0){
                        echo "al no tener una empresa cargada debe de ingresar una\n";
                        $colEm = $empresa->listar();
                        if(count($colEm)>0){
                            foreach ($colEm as $emp){
                                echo $emp;
                            }
                        }
                        echo "ingrese el id de la empresa que desea que se ingrese\n";
                        $id = trim(fgets(STDIN));
                        if($empresa->Buscar($id)){
                            echo "Informacion del Viaje:\nDestino: ";
                            $destino = trim(fgets(STDIN));
                            echo "Costo: ";
                            $costo = trim(fgets(STDIN));
                            echo "Cantidad Maxima de Personas: ";
                            $cantMaxPersonas = trim(fgets(STDIN));
                            echo "Ingrese el Responsable del Viaje\n";
                            echo "-----------------------------------------\n";
                            echo "Numero De licencia: ";
                            $nroLicencia = trim(fgets(STDIN));
                            echo "Nombre: ";
                            $nomResponsable = trim(fgets(STDIN));
                            echo "Apellido: ";
                            $apelResponsable = trim(fgets(STDIN));
                            $responsable->cargar($nroLicencia, $nomResponsable, $apelResponsable);
                            if ($responsable->Insertar()) {
                                $viaje->cargar($destino, $costo, $cantMaxPersonas, $empresa, $responsable);
                                if ($viaje->Insertar()) {
                                    echo "Se cargó correctamente el viaje\n";
                                } else {
                                    echo "No se pudo insertar el viaje: " . $viaje->getMensajeOperacion() . "\n";
                                }
                            } else {
                                echo "No se pudo insertar el responsable: " . $responsable->getMensajeOperacion() . "\n";
                            }
                        }else{
                            echo "la empresa no existe\n";
                        }
                    }else{
                        echo "Informacion del Viaje:\nDestino: ";
                        $destino = trim(fgets(STDIN));
                        echo "Costo: ";
                        $costo = trim(fgets(STDIN));
                        echo "Cantidad Maxima de Personas: ";
                        $cantMaxPersonas = trim(fgets(STDIN));
                        echo "Ingrese el Responsable del Viaje\n";
                        echo "-----------------------------------------\n";
                        echo "Numero De licencia: ";
                        $nroLicencia = trim(fgets(STDIN));
                        echo "Nombre: ";
                        $nomResponsable = trim(fgets(STDIN));
                        echo "Apellido: ";
                        $apelResponsable = trim(fgets(STDIN));
                        $responsable->cargar($nroLicencia, $nomResponsable, $apelResponsable);
                        if ($responsable->Insertar()) {
                            $viaje->cargar($destino, $costo, $cantMaxPersonas, $empresa, $responsable);
                            if ($viaje->Insertar()) {
                                echo "Se cargó correctamente el viaje\n";
                            } else {
                                echo "No se pudo insertar el viaje: " . $viaje->getMensajeOperacion() . "\n";
                            }
                        } else {
                            echo "No se pudo insertar el responsable: " . $responsable->getMensajeOperacion() . "\n";
                        }
                    }
                }
            }else{
                if($viaje->getIdViaje()!=0){
                    echo "(1)Cargar Un Pasajero\n(2)Ingresar un Pasajero al viajenOpcion: ";
                    $opcion3 = trim(fgets(STDIN));
                    if($opcion3 == 1){
                        echo "ingrese el numero de documento a buscar: ";
                        $nroDocumento = trim(fgets(STDIN));
                        if($pasajeroEstandar->Buscar($nroDocumento)){
                            echo "Pasajero Encontrado: \n";
                            echo $pasajeroEstandar;
                        }else{
                            if($pasajeroVip->Buscar($nroDocumento)){
                                echo "Pasajero Encontrado: \n";
                                echo $pasajeroVip;
                            }else{
                                if($pasajeroNecesidades->Buscar($nroDocumento)){
                                    echo "Pasajero Encontrado: \n";
                                    echo $pasajeroNecesidades;
                                }
                            }
                        }
                    }else{
                        $colEs = $pasajeroEstandar->listar($viaje->getIdViaje());
                        $colVi = $pasajeroVip->listar($viaje->getIdViaje());
                        $colNe = $pasajeroNecesidades->listar($viaje->getIdViaje());
                        $canPa = count($colEs)+count($colVi)+count($colNe);
                        if (($canPa<$viaje->getCantidadMaxPasajeros())) {
                            echo "El pasajero es: (1)Estandar,(2)VIP(3)Pasajero con Necesidades? \nOpcion: ";
                            $opcion = trim(fgets(STDIN));
                            if($opcion == 1){
                                echo "Ingrese Pasajero:\n";
                                echo "Nombre: ";
                                $nombre = trim(fgets(STDIN));
                                echo "Apellido: ";
                                $apellido = trim(fgets(STDIN));
                                echo "Nro Documento: ";
                                $nroDocumento = trim(fgets(STDIN));
                                echo "Nro de telefono: ";
                                $nroTelefono = trim(fgets(STDIN));
                                $pasajeroEstandar->cargar($nombre, $apellido, $nroDocumento, $nroTelefono,$viaje);
                                if($pasajeroEstandar->insertar()){
                                    echo "se ingreso correctamente\n";
                                }else{
                                    echo "no se ingreso correctamente\n";
                                }
                            }elseif($opcion == 2){
                                echo "Ingrese Pasajero:\n";
                                echo "Nombre: ";
                                $nombre = trim(fgets(STDIN));
                                echo "Apellido: ";
                                $apellido = trim(fgets(STDIN));
                                echo "Nro Documento: ";
                                $nroDocumento = trim(fgets(STDIN));
                                echo "Nro de telefono: ";
                                $nroTelefono = trim(fgets(STDIN));
                                echo "Nro de Viajes con Frecuencia: ";
                                $nroFrecuencia = trim(fgets(STDIN));
                                echo "Millas recorridas: ";
                                $millas = trim(fgets(STDIN));
                                $pasajeroVip->cargarVip($nombre, $apellido, $nroDocumento, $nroTelefono,$viaje,$nroFrecuencia,$millas);
                                if($pasajeroVip->Insertar()){
                                    echo "se ingreso correctamente\n";
                                }else{
                                    echo "no se ingreso correctamente\n";  
                                }
                            }else{
                                $necesidad = "";
                                echo "Ingrese Pasajero:\n";
                                echo "Nombre: ";
                                $nombre = trim(fgets(STDIN));
                                echo "Apellido: ";
                                $apellido = trim(fgets(STDIN));
                                echo "Nro Documento: ";
                                $nroDocumento = trim(fgets(STDIN));
                                echo "Nro de telefono: ";
                                $nroTelefono = trim(fgets(STDIN));
                                echo "Cuantas Necesidades tiene ?: ";
                                $nroNecesidades = trim(fgets(STDIN));
                                $necesidad="";
                                echo "ingrese la necesidad/es a continuacion: \n";
                                for ($i= 0;$i<$nroNecesidades;$i++){
                                    echo "Necesidad ".($i+1);
                                    $nec = trim(fgets(STDIN));
                                    $necesidad .= $nec ."";
                                }
                                $pasajeroNecesidades->cargarNecesidades($nombre, $apellido, $nroDocumento, $nroTelefono,$viaje,$necesidad);
                                if($pasajeroNecesidades->Insertar()){
                                    echo "se ingreso correctamente\n";
                                }else{
                                    echo "no se ingreso correctamente\n";  
                                }
                            }

                        } else {
                            echo "Se alcanzo la capacidad maxima de pasajeros\n";
                        }
                    }
                }else{
                    echo "al no aver un avije precargado se le pedira cargar uno\n";
                    $colEmp = $empresa->listar();
                    if(count($colEmp)>0){
                        foreach($colEmp as $empr){
                            $colVia = $viaje->listar($empr);
                            if(count($colVia)>0){
                                foreach($colVia as $viaj){
                                    echo "Id de viaje : " . $viaj->getIdViaje()."\n";
                                }
                            }
                        }
                    }
                    echo "Ingrese el codigo del viaje que desea ingresar: ";
                    $codigo = trim(fgets(STDIN));
                    if($viaje->Buscar($codigo)){
                        echo "viaje cargada\n";
                        echo "(1)Cargar Un Pasajero\n(2)Ingresar un Pasajero al viajen\nOpcion: ";
                        $opcion3 = trim(fgets(STDIN));
                        if($opcion3 == 1){
                            echo "ingrese el numero de documento a buscar: ";
                            $nroDocumento = trim(fgets(STDIN));
                            if($pasajeroEstandar->Buscar($nroDocumento)){
                                echo "Pasajero Encontrado: \n";
                                echo $pasajeroEstandar;
                            }else{
                                if($pasajeroVip->Buscar($nroDocumento)){
                                    echo "Pasajero Encontrado: \n";
                                    echo $pasajeroVip;
                                }else{
                                    if($pasajeroNecesidades->Buscar($nroDocumento)){
                                        echo "Pasajero Encontrado: \n";
                                        echo $pasajeroNecesidades;
                                    }
                                }
                            }
                        }else{
                            $colEs = $pasajeroEstandar->listar($viaje->getIdViaje());
                            $colVi = $pasajeroVip->listar($viaje->getIdViaje());
                            $colNe = $pasajeroNecesidades->listar($viaje->getIdViaje());
                            $canPa = count($colEs)+count($colVi)+count($colNe);
                            if ($canPa<$viaje->getCantidadMaxPasajeros()) {
                                echo "El pasajero es: (1)Estandar,(2)VIP(3)Pasajero con Necesidades? \nOpcion: ";
                                $opcion = trim(fgets(STDIN));
                                if($opcion == 1){
                                    echo "Ingrese Pasajero:\n";
                                    echo "Nombre: ";
                                    $nombre = trim(fgets(STDIN));
                                    echo "Apellido: ";
                                    $apellido = trim(fgets(STDIN));
                                    echo "Nro Documento: ";
                                    $nroDocumento = trim(fgets(STDIN));
                                    echo "Nro de telefono: ";
                                    $nroTelefono = trim(fgets(STDIN));
                                    $pasajeroEstandar->cargar($nombre, $apellido, $nroDocumento, $nroTelefono,$viaje);
                                    if($pasajeroEstandar->insertar()){
                                        echo "se ingreso correctamente\n";
                                    }else{
                                        echo "no se ingreso correctamente\n";
                                    }
                                }elseif($opcion == 2){
                                    echo "Ingrese Pasajero:\n";
                                    echo "Nombre: ";
                                    $nombre = trim(fgets(STDIN));
                                    echo "Apellido: ";
                                    $apellido = trim(fgets(STDIN));
                                    echo "Nro Documento: ";
                                    $nroDocumento = trim(fgets(STDIN));
                                    echo "Nro de telefono: ";
                                    $nroTelefono = trim(fgets(STDIN));
                                    echo "Nro de Viajes con Frecuencia: ";
                                    $nroFrecuencia = trim(fgets(STDIN));
                                    echo "Millas recorridas: ";
                                    $millas = trim(fgets(STDIN));
                                    $pasajeroVip->cargarVip($nombre, $apellido, $nroDocumento, $nroTelefono,$viaje,$nroFrecuencia,$millas);
                                    if($pasajeroVip->Insertar()){
                                        echo "se ingreso correctamente\n";
                                    }else{
                                        echo "no se ingreso correctamente\n";  
                                    }
                                }else{
                                    $necesidad = "";
                                    echo "Ingrese Pasajero:\n";
                                    echo "Nombre: ";
                                    $nombre = trim(fgets(STDIN));
                                    echo "Apellido: ";
                                    $apellido = trim(fgets(STDIN));
                                    echo "Nro Documento: ";
                                    $nroDocumento = trim(fgets(STDIN));
                                    echo "Nro de telefono: ";
                                    $nroTelefono = trim(fgets(STDIN));
                                    echo "Cuantas Necesidades tiene ?: ";
                                    $nroNecesidades = trim(fgets(STDIN));
                                    echo "ingrese la necesidad/es a continuacion: \n";
                                    $necesidad = "";
                                    for ($i= 0;$i<$nroNecesidades;$i++){
                                        echo "Necesidad ".($i+1).":\n";
                                        $nec = trim(fgets(STDIN));
                                        $necesidad .= $nec ."";
                                    }
                                    $pasajeroNecesidades->cargarNecesidades($nombre, $apellido, $nroDocumento, $nroTelefono,$viaje,$necesidad); 
                                    $res = $pasajeroNecesidades->Insertar();                               
                                    if($res){
                                        echo "se ingreso correctamente\n";
                                    }else{
                                        echo "no se ingreso correctamente\n";  
                                    }
                                }

                            } else {
                                echo "Se alcanzo la maxima capacidad del viaje\n";
                            }
                        }
                    }else{
                        echo "viaje no encontrado\n";
                    }
                }
            }
            break;
        case 2:
            echo "¿Que desea modificar?(1)Empresa(2)Viaje(3)responsable(4)pasajero\nOpcion:";
            $opcion = trim(fgets(STDIN));
            if($opcion == 1){
                $colEm = $empresa->listar();
                if(count($colEm)>0){
                    foreach($colEm as $emp){
                        echo $emp;
                    }
                    echo "ingrese el id de la empresa que desea modificar: \nid:";
                    $idMo = trim(fgets(STDIN));
                    if($empresa->Buscar($idMo)){
                        echo "Nuevo Nombre de la empresa: ";
                        $nomEm = trim(fgets(STDIN));
                        echo "Nueva Direccion de la empresa: ";
                        $direEmpre = trim(fgets(STDIN));
                        $empresa->cargar($nomEm,$direEmpre);
                        if($empresa->Modificar()){
                            echo "se modifico correctamente\n";
                        }else{
                            echo "No se pudo modificar\n";
                        }
                    }else{
                        echo "empresa no existente\n";
                    }
                }
            }elseif($opcion == 2){
                $colEm = $empresa->listar();
                if(count($colEm)>0){
                    foreach($colEm as $emp){
                        $colV = $viaje->listar($emp);
                        if(count($colV)>0){
                            foreach ($colV as $via){
                                echo "Id de viaje : " . $via->getIdViaje()."\n";
                            }
                        }
                    }
                }
                echo "ingrese el codigo del viaje a modificar\n";
                $codMod = trim(fgets(STDIN));
                if($viaje->Buscar($codMod)){
                    echo "Nuevo Informacion del Viaje:\nDestino: ";
                    $destino = trim(fgets(STDIN));
                    echo "Costo: ";
                    $costo = trim(fgets(STDIN));
                    echo "Cantidad Maxima de Personas: ";
                    $cantMaxPersonas = trim(fgets(STDIN));
                    $viaje->cargar($destino, $costo, $cantMaxPersonas, $empresa, $viaje->getResponsable());
                    if($viaje->Modificar()){
                        echo "se modifico correctamente\n";
                    }
                    else{
                        echo "no se pudo modificar correctamente \n";
                    }
                }else{
                    echo "el viaje no existe\n";
                }
            }elseif($opcion == 3){
                $colEm = $empresa->listar();
                if(count($colEm)>0){
                    foreach($colEm as $emp){
                        $colV = $viaje->listar($emp);
                        if(count($colV)>0){
                            foreach ($colV as $via){
                                echo "Id de viaje : " . $via->getIdViaje()."\n";
                            }
                        }
                    }
                }
                echo "ingrese el codigo del viaje para buscar a su responsable y modificarlo\n";
                $codMod = trim(fgets(STDIN));
                if($viaje->Buscar($codMod)){
                    $responsa = $viaje->getResponsable();
                    if($responsable->Buscar($responsa->getNumeroEmpleado())){
                        echo "ingrese el nuevo nombre: ";
                        $nuevoNom = trim(fgets(STDIN));
                        echo "ingrese el nuevo apellido: ";
                        $nuevoApel = trim(fgets(STDIN));
                        $responsable->cargar($nuevalic,$nuevoNom,$nuevoApel);
                        if($responsable->modificar()){
                            echo "se modifico correctamente\n";
                        }else{
                            echo "no se pudo modificar\n";
                        }
                    }
                }
            }
            else{
                $colEm = $empresa->listar();
                if(count($colEm)>0){
                    foreach($colEm as $emp){
                        $colV = $viaje->listar($emp);
                        if(count($colV)>0){
                            foreach ($colV as $via){
                                echo "Id de viaje : " . $via->getIdViaje()."\n";
                            }
                        }
                    }
                }
                echo "ingrese el codigo del viaje para buscar el pasajero en ese viaje\n";
                $codMod = trim(fgets(STDIN));
                if($viaje->Buscar($codMod)){
                    $colEs = $pasajeroEstandar->listar($viaje->getIdViaje());
                    $colVi = $pasajeroVip->listar($viaje->getIdViaje());
                    $colNe = $pasajeroNecesidades->listar($viaje->getIdViaje());
                    $canPa = count($colEs)+count($colVi)+count($colNe);
                    if($canPa>0){
                        $encot = false;
                        echo "Ingrese el número de documento del pasajero: ";
                        $nroBuscar = trim(fgets(STDIN));
                        if($pasajeroEstandar->Buscar($nroBuscar)){
                            $encot = true;
                            echo "es un estandar\n";
                            echo "Ingrese el nuevo nombre: ";
                            $nuevoNombre = trim(fgets(STDIN));
                            echo "Ingrese el nuevo apellido: ";
                            $nuevoApellido = trim(fgets(STDIN));
                            echo "Ingrese el nuevo número de teléfono: ";
                            $nuevoNroTelefono = trim(fgets(STDIN));
                            $pasajeroEstandar->cargar($nuevoNombre,$nuevoApellido,$nroBuscar,$nuevoNroTelefono,$viaje);
                            if($pasajeroEstandar->modificar()){
                                echo "se modifico correctamente\n";
                            }else{
                                echo "no se pudo modificar correctamente\n";
                            }
                        }
                        if($pasajeroVip->Buscar($nroBuscar)){
                            $encot = true;
                            echo "es un vip\n";
                            echo "Ingrese el nuevo nombre: ";
                            $nuevoNombre = trim(fgets(STDIN));
                            echo "Ingrese el nuevo apellido: ";
                            $nuevoApellido = trim(fgets(STDIN));
                            echo "Ingrese el nuevo número de teléfono: ";
                            $nuevoNroTelefono = trim(fgets(STDIN));
                            echo "ingrese los nuevos viajes con frecuencia: ";
                            $nuevoFrecuencia = trim(fgets(STDIN));
                            echo "ingrese las nuevas millas recorridas: ";
                            $nuevoMillas = trim(fgets(STDIN));
                            $pasajeroVip->cargarVip($nuevoNombre,$nuevoApellido,$nroBuscar,$nuevoNroTelefono,$viaje,$nuevoFrecuencia,$nuevoMillas);
                            if($pasajeroVip->modificar()){
                                echo "se modifico correctamente\n";
                            }else{
                                echo "no se pudo modificar correctamente\n";
                            }
                        }
                        if($pasajeroNecesidades->Buscar($nroBuscar)){
                            $encot = true;
                            echo "es un necesidad\n";
                            echo "Ingrese el nuevo nombre: ";
                            $nuevoNombre = trim(fgets(STDIN));
                            echo "Ingrese el nuevo apellido: ";
                            $nuevoApellido = trim(fgets(STDIN));
                            echo "Ingrese el nuevo número de teléfono: ";
                            $nuevoNroTelefono = trim(fgets(STDIN));
                            echo "ingrese las Nuevas Necesidades del pasajero: ";
                            $nuevaNecesidad = trim(fgets(STDIN));
                            $pasajeroNecesidades->cargarNecesidades($nuevoNombre,$nuevoApellido,$nroBuscar,$nuevoNroTelefono,$viaje,$nuevaNecesidad);
                            if($pasajeroNecesidades->modificar()){
                                echo "se modifico correctamente\n";
                            }else{
                                echo "no se pudo modificar correctamente\n";
                            }
                        }
                        if($encot == false){
                            echo "el pasajero no existe\n";
                        }
                    }else{
                        echo "no hay pasajeros para modificar\n";
                    }
                }else{
                    echo "no se encontro el viaje\n";
                }
            }
            break;
        case 3:
            echo "¿Que desea Eliminar?(1)Empresa(2)Viaje(3)pasajero\nOpcion:";
            $opcion = trim(fgets(STDIN));
            if($opcion == 1){
                $colEm = $empresa->listar();
                if(count($colEm)>0){
                    foreach($colEm as $emp){
                        echo $emp;
                    }
                    echo "ingrese el id de la empresa que desea Eliminar: \nid:";
                    $idEli = trim(fgets(STDIN));
                    if($empresa->Buscar($idEli)){
                        if($empresa->Eliminar()){
                            echo "se elimino la empresa y su contenido de viajes, responsables y pasajeros\n";
                        }
                    }
                }
            }elseif($opcion == 2){
                $colEm = $empresa->listar();
                if(count($colEm)>0){
                    foreach($colEm as $emp){
                        $colV = $viaje->listar($emp);
                        if(count($colV)>0){
                            foreach ($colV as $via){
                                echo "Id de viaje : " . $via->getIdViaje()."\n";
                            }
                        }
                    }
                }
                echo "ingrese el codigo del viaje a Eliminar\n";
                $codMod = trim(fgets(STDIN));
                if($viaje->Buscar($codMod)){
                    if($viaje->Eliminar()){
                        echo "se elimino el viaje, el responsable y sus pasajero\n";
                    }else{
                        echo "no se pudo eliminar el viaje".$viaje->getMensajeOperacion()."\n";
                    }
                }else{
                    echo "el viaje no existe\n";
                }
            }else{
                $colEm = $empresa->listar();
                if(count($colEm)>0){
                    foreach($colEm as $emp){
                        $colV = $viaje->listar($emp);
                        echo "hola";
                        if(count($colV)>0){
                            foreach ($colV as $via){
                                echo "Id de viaje : " . $via->getIdViaje()."\n";
                            }
                        }
                    }
                }
                echo "ingrese el codigo del viaje para buscar el pasajero en ese viaje\n";
                $codMod = trim(fgets(STDIN));
                if($viaje->Buscar($codMod)){
                    $colEs = $pasajeroEstandar->listar($viaje->getIdViaje());
                    $colVi = $pasajeroVip->listar($viaje->getIdViaje());
                    $colNe = $pasajeroNecesidades->listar($viaje->getIdViaje());
                    $canPa = count($colEs)+count($colVi)+count($colNe);
                    if($canPa>0){
                        echo "Ingrese el número de documento del pasajero: ";
                        $nroBuscar = trim(fgets(STDIN));
                        $rep = false;
                        $res = $pasajeroEstandar->Buscar($nroBuscar);
                        if($res){
                            $rep = true;
                            echo "es un pasajero estandar\n";
                            if($pasajeroEstandar->eliminar()){
                                echo "se elimino el pasajero\n";
                            }
                        }$res = $pasajeroVip->Buscar($nroBuscar);
                        if($res){
                            $rep = true;
                            echo "es un pasajero VIP\n";
                            if($pasajeroVip->eliminar()){
                                echo "se elimino el pasajero\n";
                            }
                        }
                        $res = $pasajeroNecesidades->Buscar($nroBuscar);
                        if($res){
                            $rep = true;
                            echo "es un pasajero con Necesidades\n";
                            if($pasajeroNecesidades->eliminar()){
                                echo "se elimino el pasajero\n";
                            }
                        }
                    }else{
                        echo "no se encontro el viaje\n";
                    }
                }
            }   
            break;
        case 4:
            echo "Esto son todas las empresas y su informacion de viajes, responsables y pasajeros\n";
            $colEm = $empresa->listar();
            if(count($colEm)>0){
                echo "-----------EMPRESAS-------------\n";
                foreach($colEm as $em){
                    echo "-----------------------------\n";
                    echo $em;
                    $colVi = $viaje->listar($em);
                    if(count($colVi)>0){
                        echo "----------VIAJES------------------\n";
                        foreach($colVi as $via){
                            echo "-------------------------------------\n";
                            echo $via;
                            echo "----------PASAJEROS ESTANDAR------------------\n";
                            $colE = $pasajeroEstandar->listar($via->getIdViaje());
                            if(count($colE)>0){
                                foreach($colE as $paE){
                                    echo "-------------------------------------\n";
                                    echo $paE;
                                }
                            }else{
                                echo "no hay pasajeros Estandar\n";
                            }
                            echo "\n----------PASAJEROS VIP------------------\n";
                            $colV = $pasajeroVip->listar($via->getIdViaje());
                            if(count($colV)>0){
                                foreach($colV as $paV){
                                    echo "-------------------------------------\n";
                                    echo $paV;
                                }
                            }else{
                                echo "no hay pasajeros VIP\n";
                            }
                            echo "\n----------PASAJEROS CON NECESIDADES------------------\n";
                            $colN = $pasajeroNecesidades->listar($via->getIdViaje());
                            if(count($colN)>0){
                                foreach($colN as $paN){
                                    echo "-------------------------------------\n";
                                    echo $paN;
                                }
                            }
                            else{
                                echo "no hay pasajeros Con Necesidades\n";
                            }
                        }
                    }else{
                        echo "no hay viajes\n";
                    }
                }
            }else{
                echo "no hay empresas\n";
            }
            break;
    }
    if($dese!=5){
        echo "\nQue otra cosa desea hacer? \n";
    }
}while($dese!=5);
echo "Adios, Que tenga linda tarde\n";