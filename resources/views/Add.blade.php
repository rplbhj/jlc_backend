<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>add</title>
</head>

<body>
    <div>
        <form action={{ route('add') }} method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" placeholder="name_product" name="name_product">
            <input type="text" placeholder="description_product" name="description_product">
            <input type="text" placeholder="harga_product" name="harga_product">
            <input type="text" placeholder="id_kategori" name="id_kategori">
            <input type="file" name="cover_image_product">
            <input type="text" placeholder="type_car" name="type_car">
            <input type="text" placeholder="nik_car" name="nik_car">
            <input type="file" name="images[]" multiple>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>

</html>
