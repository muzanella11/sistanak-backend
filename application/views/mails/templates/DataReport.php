<html>
    <head>
        <title>
            Data Report
        </title>
        <style>
            body {
                font-size: 12px;
                font-family: georgia;
            }
            header {
                text-align: center;
                border-bottom: 5px solid black;
            }
            .instansi {
                font-size: 18px;
            }
            .instansi.instansi--region {
                font-size: 15px;
                font-weight: bold;
            }
            .instansi.instansi--name {
                font-weight: bold;
            }
            .instansi.instansi--address {
                font-size: 12px;
            }
            
            .content {
              
            }
            
            .content .date-mail {
              margin-top: 15px;
              text-align: right;
            }

            .content .content__main {
              margin-top: 15px;
            }
            
            .content .assign {
              margin-top: 15px;
              width: 200px;
              padding-right: 30px;
              float: right;
              text-align:center;
              font-weight: bold;
            }
            
            .content .assign .assign__instansi {
              margin-bottom: 80px;
            }
            
            .content .assign .assign__name {
              border-bottom: 2px solid black;
            }
            
            .content .assign .assign__nik {
              font-weight: normal;
            }

            .content .content__table {
                boder: 1px solid black;
            }

            .theadMe td {
                border-bottom: 3px solid rgba(0,0,0,0.1);
                border-top: 3px solid rgba(0,0,0,0.1);
                background: #1ab4e8;
            }

            .tableContent td {
                border-bottom: 1px solid rgba(0,0,0,.05);
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="instansi instansi--region" style="font-weight: bold; font-size: 15px;">
              <?php echo $headerConfig['instansi']['region']; ?>
            </div>
            <div class="instansi instansi--name" style="font-weight: bold;">
              <?php echo $headerConfig['instansi']['name']; ?>
            </div>
            <div class="instansi instansi--address" style="font-size: 12px;">
              <?php echo $headerConfig['instansi']['address']; ?>
            </div>
        </header>
        
        <main class="content">
          <div class="date-mail">
            <?php echo $dateMail; ?>
          </div>
          <div style="position: relative; text-align: center; font-size: 15px; font-weight: bold; letter-spacing: 1.5;">
            <?php echo $titleContent; ?>
          </div>
          <div class="content__main">
            <?php echo $contentMain; ?>

            <?php if (isset($contentTable) && isset($tableName)) { ?>
                <?php
                    $this->load->view('mails/templates/tables/'.$tableName.'Table', $contentTable);    
                ?>
            <?php } ?>

            <!-- <div class="content__table">
                <div class="table__header">
                    <div class="td" style="width: 12%;">
                        Nama
                    </div>
                    <div class="td" style="width: 12%;">
                        Nik
                    </div>
                    <div class="td" style="width: 12%;">
                        Email
                    </div>
                    <div class="td" style="width: 12%;">
                        Username
                    </div>
                    <div class="td" style="width: 12%;">
                        Telepon
                    </div>
                    <div class="td" style="width: 12%;">
                        Role
                    </div>
                    <div class="td" style="width: 12%;">
                        Alamat
                    </div>
                    <div class="td" style="width: 12%;">
                        Status Penugasan
                    </div>
                </div>

                <div class="tr">
                    <div class="td" style="width: 12%;">
                        Siapa
                    </div>
                    <div class="td" style="width: 12%;">
                        1239888888
                    </div>
                    <div class="td" style="width: 12%;">
                        a@bcoooom.com
                    </div>
                    <div class="td" style="width: 12%;">
                        siapahayo
                    </div>
                    <div class="td" style="width: 12%;">
                        1288888
                    </div>
                    <div class="td" style="width: 12%;">
                        Petugas UPT
                    </div>
                    <div class="td" style="width: 12%;">
                        Mana coba
                    </div>
                    <div class="td" style="width: 12%;">
                        Ya
                    </div>
                </div>
            </div> -->
          </div>
        </main>
    </body>
</html>