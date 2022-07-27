<?php

ini_set('display_errors',"On");

$name = isset($_POST['name']) ? $_POST['name'] : "";
$region = isset($_POST['region']) ? $_POST['region'] : "";
$indepyear_min = isset($_POST['indepyear_min']) ? $_POST['indepyear_min'] : 0;
$indepyear_max = isset($_POST['indepyear_max']) ? $_POST['indepyear_max'] : 0;
$submit = isset($_POST['index_max']) ? $_POST['index_max'] : "";
$continent = isset($_POST['continent']) ? $_POST['continent'] : "";
$surfacearea_min = isset($_POST['surfacearea_min']) ? $_POST['surfacearea_min'] : 0;
$surfacearea_max = isset($_POST['surfacearea_max']) ? $_POST['surfacearea_max'] : 0;

try{

// MySQLiコネクタを作成
$link = mysqli_connect("localhost","root","","world");

// DBコネクションを確立
if(!$link){
    die("コネクションエラー");
}

// SQL文を生成
$query = "SELECT Code, Name, Continent, Region, IndepYear, SurfaceArea  FROM Country  ORDER BY Code LIMIT 30";

    if($submit === "search"){
        $wheres = []; 
    if($name !== ""){
        $wheres[] = "Name LIKE '%{$name}%'";
    }
    if($region !== ""){
        $wheres[] = "Region LIKE '%{$region}%'";
    }
    if($continent !== ""){
        $wheres [] = "Continent = '{$continent}'";
    }

    if(!empty($indepyear_min) && !empty($indepyear_max)){
        $wheres[] = "IndepYear BETWEEN {$indepyear_min} AND {$indepyear_max}";
    }else if(!empty($indepyear_min)){
        $wheres[] = "IndepYear >= {$indepyear_min}";
    }else if(!empty($indepyear_max)){
        $wheres[] = "IndepYear <= {$indepyear_max}";
    }

    if(!empty($surfacearea_min) && !empty($surfacearea_max)){
        $wheres[] = "SurfaceArea BETWEEN {$surfacearea_min} AND {$surfacearea_max}";
    }else if(!empty($surfacearea_min)){
        $wheres[] = "SurfaceArea >= {$surfacearea_min}";
    }else if(!empty($surfacearea_max)){
        $wheres[] = "SurfaceArea <= {$surfacearea_max}";
    }
    

    if(!empty($wheres)){
        $whires = implode(' AND ' , $wheres );
        $query = "SELECT Code, Name, Continent, Region, IndepYear, SurfaceArea  FROM Country WHERE {$whires} ORDER BY Code LIMIT 30";
    }
}

// SQl文を実行、結果を変数に格納
$result = mysqli_query($link, $query);

// DBコネクションを切断
mysqli_close($link);
} catch(\Exception $e){
    die("例外処理");
}
?>

<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<title>Hello,wold</title>
</head>
<body>
    <form class="container" method = "POST" action = "./search-country.php">
    <div class="row mb-3">
     <label for="Continent" class="col-sm-2 col-form-label">Continent</label>
         <div id="continent" value = "<?php echo $continent; ?>">
            <select class="form-select" name="continent" aria-label="Default select example" >
                <option <?php if( $continent === '') echo 'selected'; ?> value="Asia">Asia</option>
                <option <?php if( $continent === '') echo 'selected'; ?> value="Europe">Europe</option>
                <option <?php if( $continent === '') echo 'selected'; ?> value="North America">North America</option>
                <option <?php if( $continent === '') echo 'selected'; ?> value="Africa">Africa</option>
                <option <?php if( $continent === '') echo 'selected'; ?> value="Oceania">Ocenia</option>
                <option <?php if( $continent === '') echo 'selected'; ?> value="Autarctica">Autarctica</option>
                <option <?php if( $continent === '') echo 'selected'; ?> value="South America">South Ame</option>
            </select>
         </div>
    </div>
         <div class="row mb-3">
          <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputEmail3" name="name" value = "<?php echo $name; ?>">
         </div>
     </div>
         <div class="row mb-3">
          <label for="inputEmail3" class="col-sm-2 col-form-label">Region</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="region" name="region" value = "<?php echo $region; ?>">
         </div>
     </div>
     </div>
         <div class="row mb-3">
          <label for="indepyear_min" class="col-sm-2 col-form-label">IndepYear</label>
          <div class="col-sm-10">
            <div class = "input-group">
            <input type="number" class="form-control" id="indepyear_min" name="indepyear_min" value = "<?php echo $indepyear_min; ?>">
            <div class = "input-group-text">~</div>
            <input type="number" class="form-control" id="indepyear_max" name="indepyear_max" value = "<?php echo $indepyear_max; ?>">
            </div>
          </div>
         </div>
     </div>
         <div class="row mb-3">
          <label for="SurfaceArea_min" class="col-sm-2 col-form-label">SurfaceArea</label>
          <div class="input-group">
            <input type="number" class="form-control" id="surfaearea_min" name="surfacearea_min" value = "<?php echo $surfacearea_min; ?>">
            <div class="input-group-text">~</div>
            <input type="number" class="form-control" id="surfaearea_max" name="surfacearea_max" value = "<?php echo $surfacearea_max; ?>">
         </div>
     </div>
     <button type="submit" class="btn btn-primary" name= "index_max" value = "search">検索</button>
    </form>

    <table class = "table">
    <thead>
    <th>Code</th>
    <th>Name</th>
    <th>Continent</th>
    <th>Region</th>
    <th>SurfaceArea</th>
    <th>IndepYear</th>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($result)) { ?> 
        <tr>
        <td><?php echo $row [ 'Code' ]; ?></td>
        <td><?php echo $row [ 'Name' ]; ?></td>
        <td><?php echo $row [ 'Continent' ]; ?></td>
        <td><?php echo $row [ 'Region' ]; ?></td>
        <td><?php echo $row [ 'SurfaceArea' ]; ?></td>
        <td><?php echo $row [ 'IndepYear' ]; ?></td>
        </tr>
    <?php } ?> 
    </tbody>
</table>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
-->
</body>
</html>