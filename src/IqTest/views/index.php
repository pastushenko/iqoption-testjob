<!DOCTYPE html>
<html>
<head>
    <title>IqOption Posts</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
</head>
<body>
    <h1>Guestbook</h1>
    <div class="col-lg-6 col-lg-offset-3">
        <h2>Add post</h2>
        <form method="post" action="/">
            <div class="form-group">
                <label for="username">User Name:</label>
                <input name="username" type="text" class="form-control" id="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input name="email" type="text" class="form-control" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="homepage">Homepage:</label>
                <input name="homepage" type="text" class="form-control" id="homepage" placeholder="http://">
            </div>
            <div class="form-group">
                <label for="text">Text:</label>
                <textarea name="text" class="form-control" id="text" placeholder="text"></textarea>
            </div>
            <button type="submit" class="btn btn-default">Add post</button>
        </form>
    </div>
</body>
</html>