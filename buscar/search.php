<?php
// Configuración de la conexión a la base de datos
$host = 'localhost:3307';
$username = 'root';
$password = '';
$dbname = 'empresa';

// Conexión a la base de datos
$connection = new mysqli($host, $username, $password, $dbname);
if ($connection->connect_error) {
  die('Error en la conexión: ' . $connection->connect_error);
}

// Parámetros de paginación
$resultsPerPage = 10; // Cantidad de resultados por página
$page = $_POST['page']; // Página actual
$startFrom = ($page - 1) * $resultsPerPage; // Registro inicial para la consulta

// Consulta a la base de datos
$searchTerm = $_POST['search']; // Término de búsqueda
$sql = "SELECT * FROM empleado WHERE nombre LIKE '%$searchTerm%' OR apellido LIKE '%$searchTerm%' LIMIT $startFrom, $resultsPerPage";
$result = $connection->query($sql);

// Construcción de la tabla de resultados
$output = '<table>';
$output .= '<tr><th>ID</th><th>Nombre</th><th>Apellido</th></tr>';
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $output .= '<tr>';
    $output .= '<td>' . $row['ci'] . '</td>';
    $output .= '<td>' . $row['nombre'] . '</td>';
    $output .= '<td>' . $row['apellido'] . '</td>';
    $output .= '</tr>';
  }
} else {
  $output .= '<tr><td colspan="4">No se encontraron resultados.</td></tr>';
}
$output .= '</table>';

// Paginación
$sql = "SELECT COUNT(*) AS total FROM empleado WHERE nombre LIKE '%$searchTerm%' OR apellido LIKE '%$searchTerm%'";
$result = $connection->query($sql);
$row = $result->fetch_assoc();
$totalPages = ceil($row['total'] / $resultsPerPage);

$output .= '<div class="pagination">';
for ($i = 1; $i <= $totalPages; $i++) {
  $output .= '<li><a href="#" data-page="' . $i . '"';
  if ($i == $page) {
    $output .= ' class="active"';
  }
  $output .= '>' . $i . '</a></li>';
}
$output .= '</div>';

// Cierre de la conexión a la base de datos
$connection->close();

echo $output;
?>
