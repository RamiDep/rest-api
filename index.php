<?php
$host = 'localhost';
$user = "root";
$password = "";
$database = "rest-api";
$port = 3307;

$conexion = new mysqli($host, $user, $password, $database, $port);

if($conexion->connect_error){
    die("Error". $conexion->connect_error);
}

header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];
//print_r($method);
//METODO DELETE
//VERIFICAMOS QUE LA URL TENGA INFORMACION, SI EXISTE, LA GUARDAMOS EN PATH
$path = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
//BUSCAMOS EL ID, SEPARANDO LA CADENA ANTERIOR CON /
$searchId = explode('/', $path);
//intenta extraer el último elemento de $searchId utilizando la función end()
$id = ($path!=='/')? end ($searchId):null;

switch ($method) {
    case 'GET':
    //echo "Consulta de registros -GET";
    methodGet($conexion);
      break;
    case 'POST':
      echo "Consulta de registros -POST";
      methodPost($conexion);
      break;
    case 'PUT':
      methodUpdate($conexion, $id);
      break;
    case 'DELETE':
    echo "Eliminacion de registros - DELETE";
    methodDelete($conexion, $id);
      break;
    default:
    echo "Metodo no permitido";
    break;
  }

  function methodGet($conexion){
    $sql = "SELECT * from empleado";
    $result = $conexion->query($sql);

    if($result){
        $datos = array();
        while($fila = $result->fetch_assoc()){
            $datos[] = $fila;
        }
        echo json_encode($datos);
    }

  }

  function methodPost($conexion){
    $dato = json_decode(file_get_contents('php://input'),true);
    $name = $dato['name'];
    $age = $dato['age'];
    
    $sql = "INSERT INTO empleado (name, age) VALUES ('$name', '$age')";
    $result = $conexion->query($sql);

    if($result){
        $dato['id'] = $conexion->insert_id;
        echo json_encode($dato);
    }else{
        echo json_encode(array('error'=>'Error al crear usuario'));
    }

  }

  function methodDelete($conexion, $id){
    //echo "El id es: ".$id;
    $sql = "DELETE FROM empleado WHERE id = $id";
    $result = $conexion->query($sql);

    if($result){
      echo json_encode(array('Mensaje'=>'Usuario eliminado'));
    }else{
      echo json_encode(array('error'=>'Error al borrar usuario'));
    }
  }

  function methodUpdate($conexion, $id){
    //echo "El id es: ".$id;
    $dato = json_decode(file_get_contents('php://input'),true);
    $name = $dato['name'];
    $age = $dato['age'];
    $sql = "UPDATE empleado SET name='$name', age='$age' WHERE id = $id";
    $result = $conexion->query($sql);

    if($result){
      echo json_encode(array('Mensaje'=>'Usuario modificado'));
    }else{
      echo json_encode(array('error'=>'Error al modificar usuario'));
    }
  }

?>
