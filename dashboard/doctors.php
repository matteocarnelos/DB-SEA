<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Matteo Carnelos">
  <title>Pannello di controllo</title>

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/feather-icons"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link href="includes/css/dashboard.css" rel="stylesheet">

  <!-- Favicons -->
  <?php include 'includes/frame/favicons.html' ?>
</head>

<body>
  <!-- Navbar -->
  <?php include 'includes/frame/navbar.html' ?>

  <div class="container-fluid">
    <!-- Sidebar -->
    <div class="row">
      <?php
      $active = 2;
      include 'includes/frame/sidebar.php';
      ?>

      <!-- Main area -->
      <main role="main" class="col-10 ml-auto px-4">
        <h2 class="pb-2 pt-3 mb-3 border-bottom">Medici</h2>

        <?php include 'includes/handler/error_handler.php' ?>
        <?php include 'includes/handler/connection_handler.php' ?>
        <?php include 'includes/manager/doctors_manager.php' ?>

        <div class="card">
          <div class="card-header p-0">
            <button class="btn btn-block btn-link text-left d-flex align-items-center text-dark" type="button" data-toggle="collapse" data-target="#newDoctorCollapse">
              <i class="mr-1" data-feather="plus" style="width: 24px; height: 24px"></i>
              <h5 class="pt-2">Registra medico</h5>
            </button>
          </div>
          <div id="newDoctorCollapse" class="collapse <?php if (isset($_GET['show'])) echo 'show' ?>">
            <div class="card-body">
              <form class="needs-validation" method="post" action="doctors.php?new" novalidate>

                <div class="form-group row">
                  <label class="col-1 col-form-label" for="idInput">ID</label>
                  <div class="col-2">
                    <input type="text" class="form-control text-uppercase" id="idInput" name="id" placeholder="0123456789" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-1 col-form-label" for="nameInput">Nome</label>
                  <div class="col-4">
                    <input type="text" class="form-control" id="nameInput" name="name" placeholder="Mario" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-1 col-form-label" for="surnameInput">Cognome</label>
                  <div class="col-4">
                    <input type="text" class="form-control" id="surnameInput" name="surname" placeholder="Rossi" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-1 col-form-label" for="phoneInput">Telefono</label>
                  <div class="col-3">
                    <input type="text" class="form-control text-uppercase" id="phoneInput" name="phone" placeholder="+393401234567">
                  </div>
                  <small class="text-muted my-auto">Se non comunicato, lasciare vuoto il campo.</small>
                </div>

                <div class="row justify-content-end border-top pt-3">
                  <div class="col-3">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Registra</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-baseline">
          <h4 class="m-3">Lista medici registrati</h4>
          <p class="text-dark">Numero tuple: <?php echo pg_fetch_result(pg_query('SELECT COUNT(*) FROM "MEDICO"'), 0) ?></p>
        </div>

        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Cognome</th>
              <th scope="col">Nome</th>
              <th scope="col">Telefono</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>

            <?php
            $query = '
              SELECT *
              FROM "MEDICO"
              ORDER BY cognome, nome
            ';
            $doctors = pg_query($query);
            $index = 0;
            while ($doctor = pg_fetch_array($doctors, null, PGSQL_ASSOC)) {
              $index++;
            ?>

              <tr>
                <th scope="row">
                  <?php echo $doctor['id'] ?>
                </th>
                <td>
                  <?php echo $doctor['cognome'] ?>
                </td>
                <td>
                  <?php echo $doctor['nome'] ?>
                </td>
                <td>
                  <?php
                  if (!isset($doctor['telefono'])) echo '<p class="font-italic text-muted">Non rilasciato</p>';
                  else echo $doctor['telefono'];
                  ?>
                </td>
                <td class="align-middle text-right">
                  <?php if (!isset($doctor['telefono'])) { ?>
                    <button class="btn btn-outline-info mr-1" type="button" data-toggle="modal" data-target="#addTelModal<?php echo $index ?>">
                      <i data-feather="phone"></i>
                      Aggiungi telefono
                    </button>
                  <?php } ?>
                  <button class="btn btn-outline-danger" type="button" data-toggle="modal" data-target="#removeModal<?php echo $index ?>">
                    <i data-feather="trash-2"></i>
                  </button>
                </td>
              </tr>

              <div class="modal fade" id="removeModal<?php echo $index ?>">
                <div class="modal-dialog modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Rimozione medico <?php echo "{$doctor['nome']} {$doctor['cognome']}" ?></h5>
                      <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                      </button>
                    </div>
                    <form class="needs-validation" method="post" action="doctors.php?remove" novalidate>
                      <input type="hidden" name="id" value="<?php echo $doctor['id'] ?>">
                      <div class="modal-body">
                        Sei sicuro di voler cancellare il medico?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-danger">Cancella</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <div class="modal fade" data-backdrop="static" id="addTelModal<?php echo $index ?>">
                <div class="modal-dialog modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Modifica medico <?php echo "{$doctor['nome']} {$doctor['cognome']}" ?></h5>
                      <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                      </button>
                    </div>
                    <form method="post" action="doctors.php?update">
                      <input type="hidden" name="id" value="<?php echo $doctor['id'] ?>">
                      <div class="modal-body">
                        <div class="form-group row">
                          <label class="col-3 col-form-label" for="modPhoneInput">Telefono</label>
                          <div class="col-9">
                            <input type="text" class="form-control text-uppercase" id="modPhoneInput" name="phone" placeholder="+393401234567" required>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-info">Aggiungi</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            <?php } ?>

          </tbody>
        </table>
      </main>
    </div>
  </div>

  <script>
    feather.replace();
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>
</body>

</html>