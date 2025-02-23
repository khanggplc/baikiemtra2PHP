<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP Example</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>

        <form class="row" method="POST" enctype="multipart/form-data">
            <div class="col">
                <div class="mb-3">
                    <input type="file" accept=".txt" class="form-control" name="file">
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Import database</button>
            </div>
            <div class="col">
            </div>
        </form>
        <?php
         $server = "localhost";
         $database = "db_hoang_van_khang";
         $username = "root";
         $password = "";
 
         $conn = new mysqli($server, $username, $password, $database);
 
         if ($conn->connect_error) {
             die('Connect failed: ' . $conn->connect_error);
        }
        ?>
        <?php
        if (isset($_POST['submit'])) {
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                // Nhận đường dẫn file tạm
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                $fileType = $_FILES['file']['type'];

                // Kiểm tra xem file có phải là TXT hay không
                if ($fileType == 'text/plain') {
                    // 1. Đọc dữ liệu file txt
                    function handleFile($data)
                    {
                
                    array_pop($data);
                    for($i=0; $i<count($data); $i++)
                    {
                        $data[$i] = str_replace('"', "", $data[$i]);
                        $data[$i] = explode(",", $data[$i]);
                    }
                    return $data;
                    }
                    $data = [];
                    $file = @fopen($fileName, 'r');
                    if (!$file)
                    {
                        echo "Mo file khong thanh cong";
                    }
                    else
                    {
                        while(!feof($file))
                        {
                            array_push($data, fgets($file));
                        }
                    }
                    $data=handleFile($data);
                    // 2. Insert dữ liệu vào database. Chú ý: nếu bản ghi có Title đã tồn tại thì bỏ qua bản ghi đó
                    $server = "localhost";
                    $database = "db_hoang_van_khang";
                    $username = "root";
                    $password = "";
                    
                    $connect = mysqli_connect($server, $username, $password, $database);
                    if($connect)
                    {
                        mysqli_query($connect, "Set NAMES 'UTF8'");
                    }
                    else{
                        echo "Ket noi that bai";
                    }
                    $true = 0;
                    $false = 0;
                    for ($i=0; $i<count($data); $i++){
                        $title = $data[$i][0];
                        $description = $data[$i][1];
                        $imageUrl = $data[$i][2];
                        
                        

                        $sql_check = "SELECT *FROM course";
                        $query_check = mysqli_query($connect, $sql_check);
                        $check = true;
                        while ($row=mysqli_fetch_assoc($query_check))
                        {
                            if ($row['Title']==$data[$i][0])
                            {
                                $check = false;
                            }
                        }
                        if ($check == true)
                        {
                            $sql = "INSERT INTO course (Title, Description, ImageUrl) VALUE ('$title','$description','$imageUrl')";
                            $query = mysqli_query($connect, $sql);
                            $true += 1;
                        }
                        else if ($check == false)
                        {
                            $false += 1;
                        }                      

                    }
                    
                    // 3. Thông báo ra màn hình số bản ghi insert thành công, số bản ghi insert không thành công
                    ?> <div class="alert alert-info mt-2" role="alert">
                    <?php echo $true ?> records inserted successfully, <?php echo $false ?> records inserted failed
                </div> <?php
                    fclose($file);
                } else {
                    echo '<div class="alert alert-warning" role="alert">
                            Vui lòng tải lên file .txt!
                        </div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">
                            Lỗi tải file!
                        </div>';
            }
        }
        ?>

        <hr>

        <?php

        $server = "localhost";
        $database = "db_hoang_van_khang";
        $username = "root";
        $password = "";

        $conn = new mysqli($server, $username, $password, $database);

        if ($conn->connect_error) {
            die('Connect failed: ' . $conn->connect_error);
        }

        $query = "SELECT * FROM course";
        $result = $conn->query($query);

        echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
        if ($result->num_rows > 0) {
            while ($course = $result->fetch_assoc()) {
        ?>
                <div class="col">
                    <div class="card">
                        <img src="<?= htmlspecialchars($course['ImageUrl']) ?>" class="card-img-top" alt="<?= htmlspecialchars($course['Title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['Title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($course['Description']) ?></p>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        echo ' </div>';

        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>