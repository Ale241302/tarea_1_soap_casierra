<!DOCTYPE html>
<html>
<head>
    <title>Hello Word</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Word</h2>
        <form method="post" action="">

        <div class="form-group">
                <label for="num1">Ingrese Su Nombre:</label>
                <input type="string" class="form-control" name="nomb" required>
            </div>
            
            <button type="submit" class="btn btn-primary" name="calcular">Enviar</button>
        </form>

       
        <?php
        if(isset($_POST['calcular'])) {
        $nomb = $_POST['nomb'];
        
        $options = array(
            'location' => 'https://servicios.documentosige.com.co/service.php', 
            'uri' => 'https://servicios.documentosige.com.co/service.php?wsdl', 
        );
            $client = new SoapClient(null, $options);
            $resultado2 = $client->hello($nomb);
            echo "Resultado: $resultado2";            
    }
    ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

