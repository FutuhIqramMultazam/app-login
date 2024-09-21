                   <!-- Begin Page Content -->
                   <div class="container-fluid">

                       <!-- Page Heading -->
                       <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                       <div class="row">
                           <div class="col-lg">


                               <!-- alert notifikasi -->
                               <?php if (validation_errors()): ?>
                                   <div class="row">
                                       <div class="col-md-6">
                                           <div class="alert alert-danger" role="alert"><?= validation_errors(); ?></div>

                                           <?= $this->session->flashdata('message'); ?>

                                       </div>
                                   </div>
                               <?php endif; ?>


                               <a href="" class=" mb-3 btn btn-primary" data-toggle="modal" data-target="#newSubMenuModal">Add New SubMenu</a>

                               <table class="table table-hover">
                                   <thead>
                                       <tr>
                                           <th scope="col">#</th>
                                           <th scope="col">Menu</th>
                                           <th scope="col">Title</th>
                                           <th scope="col">Url</th>
                                           <th scope="col">Icon</th>
                                           <th scope="col">Is_Active</th>
                                           <th scope="col">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>

                                       <?php $i = 1;
                                        foreach ($SubMenu as $sm): ?>
                                           <tr>
                                               <th scope="row"><?= $i ?></th>
                                               <td><?= $sm["menu"] ?></td>
                                               <td><?= $sm["title"] ?></td>
                                               <td><?= $sm["url"] ?></td>
                                               <td><?= $sm["icon"] ?></td>
                                               <td><?= $sm["is_active"] ?></td>
                                               <td>
                                                   <a href="" class="badge badge-warning text-dark">edit</a>
                                                   <a onclick="return confirm('apakah anda yakin?');" href="<?= base_url("menu/delete/") ?>" class="badge badge-danger">delete</a>
                                               </td>
                                           </tr>
                                       <?php $i++;
                                        endforeach; ?>
                                   </tbody>
                               </table>

                           </div>
                       </div>

                   </div>
                   <!-- /.container-fluid -->

                   </div>
                   <!-- End of Main Content -->




                   <!-- Modal -->
                   <div class="modal fade" id="newSubMenuModal" tabindex="-1" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
                       <div class="modal-dialog">
                           <div class="modal-content">
                               <div class="modal-header">
                                   <enu class="modal-title" id="newSubMenuModalLabel">Add New SubMenu</enu>
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                   </button>
                               </div>
                               <form action="<?= base_url('menu/submenu'); ?>" method="post">
                                   <div class="modal-body">

                                       <div class="form-group">
                                           <select name="menu_id" id="menu_id" class="form-control">
                                               <option disabled selected>-- Select SubMenu --</option>
                                               <?php foreach ($menu as $m): ?>
                                                   <option value="<?= $m["id"] ?>"><?= $m["menu"] ?></option>
                                               <?php endforeach; ?>
                                           </select>
                                       </div>

                                       <div class="form-group">
                                           <input type="text" class="form-control" id="title" name="title" placeholder="Title ....">
                                       </div>

                                       <div class="form-group">
                                           <input type="text" class="form-control" id="url" name="url" placeholder="Url ....">
                                       </div>

                                       <div class="form-group">
                                           <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon ....">
                                       </div>

                                       <div class="form-group">
                                           <div class="form-check">
                                               <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                                               <label class="form-check-label" for="is_active">
                                                   Active?
                                               </label>
                                           </div>
                                       </div>

                                   </div>
                                   <div class="modal-footer">
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                       <button type="submit" class="btn btn-primary">Add</button>
                                   </div>
                               </form>
                           </div>
                       </div>
                   </div>