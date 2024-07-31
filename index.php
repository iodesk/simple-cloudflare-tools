<?php
$config = require 'accounts.php';
$accounts = $config['cloudflare'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple CF Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://utas.cc/components/storage/app/public/photos/1/logo/ico.png" type="image/x-icon">
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css"
      rel="stylesheet"/>
    <link
      href="assets/css/fio.min.css"
      rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body style="background-color: #F1F5F9;">
    <div class="container-lg mb-2">
    <h4 class="my-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="currentColor" class="bi bi-cloud-fog2-fill" viewBox="0 0 16 16">
          <path d="M8.5 3a5 5 0 0 1 4.905 4.027A3 3 0 0 1 13 13h-1.5a.5.5 0 0 0 0-1H1.05a3.5 3.5 0 0 1-.713-1H9.5a.5.5 0 0 0 0-1H.035a3.5 3.5 0 0 1 0-1H7.5a.5.5 0 0 0 0-1H.337a3.5 3.5 0 0 1 3.57-1.977A5 5 0 0 1 8.5 3"/>
        </svg>
        <span class="title">Simple CF Manager by <b>F10</b></span>
    </h4>

      <form>
          <h6 for="account-selector">Select Account</h6>
          <select name="account" id="account-selector" class="form-select rounded shadow-sm border-0">
              <?php foreach ($accounts as $accountName => $accountDetails): ?>
                  <option value="<?= htmlspecialchars($accountName) ?>">
                      <?= htmlspecialchars($accountDetails['email']) ?>
                  </option>
              <?php endforeach; ?>
          </select>
      </form>
    
        <div class="row mb-3 mt-2">          
            <div class="col-md-4">
              <div class="fio-card shadow-sm rounded">
                 <!-- Search input -->
                  <div class="mb-3">
                    <h6>List of Zones</h6>
                  	<input type="text" id="zone-search" class="form-control rounded shadow-sm col-form-label-mg mt-2 mb-3 px-3" placeholder="Search Zone names...">
                  	<button type="button" class="btn btn-primary btn-sm shadow-none fio-btn" data-toggle="modal" data-target="#addZoneModal">Create Zone</button>
              	  </div>
                <!-- List of Zones -->
                <div id="zone-list">

                    <div class="fio-height rounded">
                      <div class="fio-notif"></div>
                        <div class="row row-cols-1 row-cols-md-1 gy-3 gx-3 py-2" id="zone-list-items">
                            <!-- Dynamic cards will be appended here -->
                        </div>
                    </div>
                </div>
            </div>
          </div>
          
            <div class="col-md-8">
              <div class="fio-card shadow-sm rounded">
                <div class="mb-3">
                    <h6 class="my-auto mb-2">DNS Records: <b><span id="zone-name" style="margin-right:10px;"></span></b></h6>                  
                    <input type="text" id="dns-search" class="form-control rounded shadow-sm col-form-label-mg px-3 mt-2 mb-3" placeholder="Search DNS records..." disabled>
                  <div class="d-flex flex-row">
                    <button class='btn btn-primary btn-sm shadow-none fio-btn add-dns' data-zone-id='{$zoneId}' disabled>Add Record</button>
                    <button class='btn btn-danger btn-sm shadow-none fio-btn mx-2 bulk-delete-dns' data-zone-id='<?php echo $zoneId; ?>' disabled>Bulk Delete</button>
                  </div>
                </div>
                <!-- List of DNS Records -->
                <div id="dns-list-section">
                    <div class="fio-height rounded">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Content</th>
                                        <th>TTL</th>
                                        <th>Proxied</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="dns-list">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div id="pesandns" class="alert alert-warning my-3">
                                                Data is empty, please click view DNS on the domain list
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- DNS records will be loaded dynamically here -->
                                </tbody>
                            </table>
                      		<div class="fio-height-end"></div>
                        </div>
                    </div>
                </div>
            </div>
          </div>

        <!-- Modal for editing DNS records -->
        <div class="modal fade" id="editDnsModal" tabindex="-1" aria-labelledby="editDnsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDnsModalLabel">Edit DNS Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-dns-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control rounded shadow-sm" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <input type="text" class="form-control rounded shadow-sm" id="type" name="type" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <input type="text" class="form-control rounded shadow-sm" id="content" name="content" required>
                            </div>
                            <div class="mb-3">
                                <label for="ttl" class="form-label">TTL</label>
                                <input type="number" class="form-control rounded shadow-sm" id="ttl" name="ttl" required>
                            </div>
                            <div class="mb-3">
                                <label for="proxied" class="form-label">Proxied</label>
                                <select class="form-select rounded shadow-sm" id="proxied" name="proxied">
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                            <input type="hidden" name="zone_id" id="zone_id">
                            <input type="hidden" name="dns_record_id" id="dns_record_id">
                            <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                              Submit
                            </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for adding DNS records -->
        <div class="modal fade" id="addDnsModal" tabindex="-1" aria-labelledby="addDnsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDnsModalLabel">Add DNS Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="add-dns-form">
                            <div class="mb-3">
                                <label for="add-name" class="form-label">Name</label>
                                <input type="text" class="form-control rounded shadow-sm" id="add-name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="add-type" class="form-label">Type</label>
                                <select class="form-select rounded shadow-sm" id="add-type" name="type" required>
                                    <option value="A">A</option>
                                    <option value="AAAA">AAAA</option>
                                    <option value="CNAME">CNAME</option>
                                    <option value="MX">MX</option>
                                    <option value="TXT">TXT</option>
                                    <option value="NS">NS</option>
                                    <option value="SRV">SRV</option>
                                    <option value="LOC">LOC</option>
                                    <option value="CAA">CAA</option>
                                    <option value="DNSKEY">DNSKEY</option>
                                    <option value="DS">DS</option>
                                    <option value="NAPTR">NAPTR</option>
                                    <option value="SMIMEA">SMIMEA</option>
                                    <option value="SSHFP">SSHFP</option>
                                    <option value="TLSA">TLSA</option>
                                    <option value="URI">URI</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="add-content" class="form-label">Content</label>
                                <input type="text" class="form-control rounded shadow-sm" id="add-content" name="content" required>
                            </div>
                            <div class="mb-3">
                                <label for="add-ttl" class="form-label">TTL</label>
                                <input type="number" class="form-control rounded shadow-sm" id="add-ttl" name="ttl" value="3600" required>
                            </div>
                            <div class="mb-3">
                                <label for="add-proxied" class="form-label">Proxied</label>
                                <select class="form-select rounded shadow-sm" id="add-proxied" name="proxied">
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                            <input type="hidden" name="zone_id" id="add-zone_id">
                            <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                              Submit
                            </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Zones Modal -->
        <div class="modal fade" id="addZoneModal" tabindex="-1" role="dialog" aria-labelledby="addZoneModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addZoneModalLabel">Add Zone</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addZoneForm">
                            <div class="mb-3">
                                <label for="domain" class="form-label">Domain</label>
                                <input type="text" class="form-control rounded shadow-sm" id="domain" name="domain" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <input type="text" class="form-control rounded shadow-sm" id="type" name="type" value="full" required readonly disabled>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                              Submit
                            </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <!-- Back to Top Button -->
    <button class="btn btn-primary back-to-top " onclick="topFunction()">&#8593;</button>
  
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="assets/js/fio.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Show the Back to Top button when scrolling down
        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.querySelector('.back-to-top').style.display = "block";
            } else {
                document.querySelector('.back-to-top').style.display = "none";
            }
        }

        // Scroll to the top of the page when the Back to Top button is clicked
        function topFunction() {
            document.body.scrollTop = 0; 
            document.documentElement.scrollTop = 0;
        }
      
    </script>
</body>
</html>
