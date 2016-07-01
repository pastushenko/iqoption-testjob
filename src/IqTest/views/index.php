<?php
use IqTest\Controller\IndexController;
use IqTest\Entity\Post;
use IqTest\Service\PostValidator;
use IqTest\Entity\PostsFilter;

/**
 * @var PostValidator $validator
 * @var Post[] $posts
 * @var bool $isPostAdded
 * @var bool $isHttpPostRequest
 * @var PostsFilter $postsFilter
 * @var int $postsCount
 */

$validator = IndexController::$layoutVars['validator'];
$posts = IndexController::$layoutVars['posts'];
$isPostAdded = IndexController::$layoutVars['isPostAdded'];
$isHttpPostRequest = IndexController::$layoutVars['isHttpPostRequest'];
$postsFilter = IndexController::$layoutVars['postsFilter'];
$postsCount = IndexController::$layoutVars['postsCount'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>IqOption Posts</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
</head>
<body>
    <h1 style="text-align: center">Guestbook</h1>
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <h2>Posts:</h2>
            <?php if (empty($posts)): ?>
                <p>There are no posts yet.</p>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Homepage</th>
                        <th>Text</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td class="col-lg-1"><?= htmlentities($post->getEmail()); ?></td>
                            <td class="col-lg-1"><?= htmlentities($post->getUsername()); ?></td>
                            <td class="col-lg-1"><?= htmlentities($post->getHomepage()); ?></td>
                            <td class="col-lg-7"><?= htmlentities($post->getText()); ?></td>
                            <td class="col-lg-2"><?= htmlentities($post->getCreatedAt()); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <p style="text-align: center">
                <?php $totalPages = ceil($postsCount / $postsFilter->getLimit()); ?>
                <?php for($page = 1; $page <= $totalPages; $page++): ?>
                    <?php if ($page == $postsFilter->getPage()): ?>
                        <span><?= $page ?></span>
                    <?php else: ?>
                        <a href="/?page=<?= $page; ?>&<?= $postsFilter->getQueryWithoutPage();?>"><?= $page ?></a>
                    <?php endif; ?>

                <?php endfor; ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <h2>Add post</h2>
            <?php if ($isHttpPostRequest && $isPostAdded): ?><h3 class="text-success">Post has been added!</h3><?php endif; ?>
            <?php if ($isHttpPostRequest && !$isPostAdded): ?><h3 class="text-danger">Post has not been added!</h3><?php endif; ?>
            <form method="post" action="/">
                <div class="form-group">
                    <label for="username">User Name*:</label>
                    <input name="<?= PostValidator::FIELD_USERNAME; ?>" type="text" class="form-control" id="username" placeholder="Username" value="<?= htmlentities($validator->getField(PostValidator::FIELD_USERNAME)); ?>">
                    <?php
                    $error = $validator->getErrorByFieldName(PostValidator::FIELD_USERNAME);
                    if ($error):
                        ?>
                        <span class="help-inline text-danger"><?= $error ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Email*:</label>
                    <input name="<?= PostValidator::FIELD_EMAIL; ?>" type="text" class="form-control" id="email" placeholder="Email" value="<?= htmlentities($validator->getField(PostValidator::FIELD_EMAIL)); ?>">
                    <?php
                    $error = $validator->getErrorByFieldName(PostValidator::FIELD_EMAIL);
                    if ($error):
                        ?>
                        <span class="help-inline text-danger"><?= $error ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="homepage">Homepage:</label>
                    <input name="<?= PostValidator::FIELD_HOMEPAGE; ?>" type="text" class="form-control" id="homepage" placeholder="http://" value="<?= htmlentities($validator->getField(PostValidator::FIELD_HOMEPAGE)); ?>">
                    <?php
                        $error = $validator->getErrorByFieldName(PostValidator::FIELD_HOMEPAGE);
                        if ($error):
                    ?>
                        <span class="help-inline text-danger"><?= $error ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="text">Text*:</label>
                    <textarea name="<?= PostValidator::FIELD_TEXT; ?>" class="form-control" id="text" placeholder="text"><?= htmlentities($validator->getField(PostValidator::FIELD_TEXT)); ?></textarea>
                    <?php
                    $error = $validator->getErrorByFieldName(PostValidator::FIELD_TEXT);
                    if ($error):
                        ?>
                        <span class="help-inline text-danger"><?= $error ?></span>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-default">Add post</button>
            </form>
        </div>
    </div>
</body>
</html>