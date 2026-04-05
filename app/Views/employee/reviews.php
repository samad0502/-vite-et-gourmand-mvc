<?php require_once ROOT . 'includes/header.php'; ?>

<div class="container my-5">
    <h2><i class="bi bi-chat-left-quote me-2"></i>Modération des avis</h2>

    <?php if (empty($pendingReviews)): ?>
        <div class="alert alert-success">Aucun avis en attente de modération.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle shadow-sm bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Client</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingReviews as $review): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($review['firstname']) ?></strong></td>
                            <td>
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill text-warning' : '' ?>"></i>
                                <?php endfor; ?>
                            </td>
                            <td><em>"<?= htmlspecialchars($review['comment']) ?>"</em></td>
                            <td>
                                <a href="index.php?page=manage_reviews&action=validate&id=<?= $review['id'] ?>" 
                                   class="btn btn-sm btn-success">Valider</a>
                                <a href="index.php?page=manage_reviews&action=refuse&id=<?= $review['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Refuser cet avis ?');">Refuser</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>