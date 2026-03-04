<?php 
// Connexion à la base de données via le fichier config
require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Campus IT</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Tableau de bord - Campus IT</h1>
    <p>Données de démonstration — base: <strong>campus_it</strong></p>

    <div class="tab">
      <button class="tablinks active" onclick="openTab(event, 'Tab1')">Top applications</button>
      <button class="tablinks" onclick="openTab(event, 'Tab2')">Évolution mensuelle</button>
      <button class="tablinks" onclick="openTab(event, 'Tab3')">Comparaison ressources</button>
    </div>

    <div id="Tab1" class="tabcontent" style="display:block;">
      <h3>Top 5 des applications (consommation totale)</h3>
      <div style="max-width: 600px; margin: 20px auto;">
          <canvas id="chartTopApps"></canvas>
      </div>
      <table>
        <thead>
            <tr><th>Application</th><th>Total (unités cumulées)</th></tr>
        </thead>
        <tbody>
        <?php
          $sql1 = "SELECT a.nom, SUM(c.volume) as total 
                   FROM application a 
                   JOIN consommation c ON a.app_id = c.app_id 
                   GROUP BY a.nom 
                   ORDER BY total DESC 
                   LIMIT 5";
          $stmt1 = $pdo->query($sql1);
          $dataApps = $stmt1->fetchAll(PDO::FETCH_ASSOC);
          foreach ($dataApps as $row) {
              echo "<tr><td>" . htmlspecialchars($row['nom']) . "</td><td>" . number_format($row['total'], 2, ',', ' ') . "</td></tr>";
          }
        ?>
        </tbody>
      </table>
    </div>

    <div id="Tab2" class="tabcontent">
      <h3>Évolution mensuelle (total campus)</h3>
      <div style="max-width: 600px; margin: 20px auto;">
          <canvas id="chartEvol"></canvas>
      </div>
      <table>
        <thead>
            <tr><th>Mois</th><th>Total (unités cumulées)</th></tr>
        </thead>
        <tbody>
        <?php
          $sql2 = "SELECT DATE_FORMAT(mois, '%Y-%m') as date_mois, SUM(volume) as total 
                   FROM consommation 
                   GROUP BY date_mois 
                   ORDER BY date_mois ASC";
          $stmt2 = $pdo->query($sql2);
          $dataEvol = $stmt2->fetchAll(PDO::FETCH_ASSOC);
          foreach ($dataEvol as $row) {
              echo "<tr><td>{$row['date_mois']}</td><td>" . number_format($row['total'], 2, ',', ' ') . "</td></tr>";
          }
        ?>
        </tbody>
      </table>
    </div>

    <div id="Tab3" class="tabcontent">
      <h3>Comparaison Stockage vs Réseau</h3>
      <table>
        <thead>
            <tr><th>Mois</th><th>Stockage (Go)</th><th>Réseau (Go)</th></tr>
        </thead>
        <tbody>
        <?php
          $sql3 = "SELECT 
                    DATE_FORMAT(c.mois, '%Y-%m') as mois,
                    SUM(CASE WHEN r.nom = 'Stockage' THEN c.volume ELSE 0 END) as stockage,
                    SUM(CASE WHEN r.nom = 'Réseau' THEN c.volume ELSE 0 END) as reseau
                   FROM consommation c
                   JOIN ressource r ON c.res_id = r.res_id
                   GROUP BY mois
                   ORDER BY mois ASC";
          $stmt3 = $pdo->query($sql3);
          while ($row = $stmt3->fetch()) {
              echo "<tr>
                      <td>{$row['mois']}</td>
                      <td>" . number_format($row['stockage'], 2, ',', ' ') . "</td>
                      <td>" . number_format($row['reseau'], 2, ',', ' ') . "</td>
                    </tr>";
          }
        ?>
        </tbody>
      </table>
    </div>

    <script src="script.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données envoyées de PHP vers JS via json_encode
        const appsData = <?php echo json_encode($dataApps); ?>;
        const evolData = <?php echo json_encode($dataEvol); ?>;

        if(appsData.length > 0) {
            new Chart(document.getElementById('chartTopApps'), {
                type: 'bar',
                data: {
                    labels: appsData.map(item => item.nom),
                    datasets: [{
                        label: 'Consommation Totale',
                        data: appsData.map(item => item.total),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                }
            });
        }

        if(evolData.length > 0) {
            new Chart(document.getElementById('chartEvol'), {
                type: 'line',
                data: {
                    labels: evolData.map(item => item.date_mois),
                    datasets: [{
                        label: 'Volume cumulé mensuel',
                        data: evolData.map(item => item.total),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                }
            });
        }
    });
    </script>
</body>
</html>