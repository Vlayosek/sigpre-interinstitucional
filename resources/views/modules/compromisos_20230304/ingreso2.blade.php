<div id="listado_asesores">
    <div class="row">
      <div class="col-lg-6 mb-3">
        <section class="card">
          <div class="card-body">
            <table class="table table-responsive-md mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Cédula</th>
                  <th>Máquina / IP</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(asesor,i) in asesores">
                  <td>{{i+1}}</td>
                  <td>{{asesor.nombre_primero}}</td>
                  <td>{{asesor.apellido_primero}}</td>
                  <td>{{asesor.cedula_id}}</td>
                  <td>{{asesor.ip}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>
      <div class="col-lg-6">
        <div class="row">
          <div class="col-xl-6 mb-3" v-for="(asesor, i) in asesores">
            <section class="card card-featured-left card-featured-quaternary">
              <div class="card-body">
                <div class="widget-summary">
                  <div class="widget-summary-col widget-summary-col-icon" @click="verPerfilJuez(i)" style="cursor:pointer">
                    <div class="summary-icon bg-quaternary">
                      <i class="fas fa-user"></i>
                    </div>
                  </div>
                  <div class="widget-summary-col">
                    <div class="summary">
                      <h4 class="title">Juez</h4>
                      <div class="info">
                        <strong class="amount">{{asesor.nombre_primero}} {{asesor.apellido_primero}}</strong>
                      </div>
                    </div>
                    <div class="summary-footer">
                      <a class="text-muted text-uppercase" href="#">{{asesor.ip}}</a>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
        <div class="col-md-12">
          <button type="button" class="mb-1 mt-1 me-1 btn btn-primary" @click="registrarPerfilJuez">
            <div class="fas fa-save"></div> Registrar juez
          </button>
        </div>
      </div>
    </div>
  
    <div class="row" v-show="accion==='verPerfil'">
      <div class="col-lg-4 col-xl-3 mb-4 mb-xl-0">
        <section class="card">
          <div class="card-body">
            <div class="thumb-info mb-3">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!logged-user.jpg") ?>" class="rounded img-fluid" alt="John Doe">
              <div class="thumb-info-title">
                <span class="thumb-info-inner">{{verPerfil.nombre_primero}} {{verPerfil.apellido_primero}}</span>
                <span class="thumb-info-type">Juez</span>
              </div>
            </div>
  
            <div class="widget-toggle-expand mb-3">
              <div class="widget-header">
                <h5 class="mb-2 font-weight-semibold text-dark">Perfil del juez</h5>
                <div class="widget-toggle">+</div>
              </div>
              <div class="widget-content-collapsed">
                <div class="progress progress-xs light">
                  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    100%
                  </div>
                </div>
              </div>
              <div class="widget-content-expanded">
                <ul class="simple-todo-list mt-3">
                  <li class="completed">Update Profile Picture</li>
                  <li class="completed">Change Personal Information</li>
                  <li>Update Social Media</li>
                  <li>Follow Someone</li>
                </ul>
              </div>
            </div>
  
            <hr class="dotted short">
  
            <h5 class="mb-2 mt-3">Acerca del juez</h5>
            <p class="text-2">{{verPerfil.acerca_de}}</p>
            <div class="clearfix">
              <a class="text-uppercase text-muted float-end" href="#">(View All)</a>
            </div>
  
            <hr class="dotted short">
  
            <div class="social-icons-list">
              <a rel="tooltip" data-bs-placement="bottom" target="_blank" href="http://www.facebook.com" data-original-title="Facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
              <a rel="tooltip" data-bs-placement="bottom" href="http://www.twitter.com" data-original-title="Twitter"><i class="fab fa-twitter"></i><span>Twitter</span></a>
              <a rel="tooltip" data-bs-placement="bottom" href="http://www.linkedin.com" data-original-title="Linkedin"><i class="fab fa-linkedin-in"></i><span>Linkedin</span></a>
            </div>
  
          </div>
        </section>
      </div>
      <div class="col-lg-8 col-xl-6">
  
        <div class="tabs">
          <ul class="nav nav-tabs tabs-primary">
            <!-- <li class="nav-item">
              <button class="nav-link" data-bs-target="#overview" data-bs-toggle="tab">Overview</button>
            </li> -->
            <li class="nav-item active">
              <button class="nav-link active" data-bs-target="#edit" data-bs-toggle="tab">Información</button>
            </li>
          </ul>
          <div class="tab-content">
            <div id="edit" class="tab-pane active">
              <form class="p-3">
  
                <div class="row row mb-4">
                  <div class="form-group col">
                    <label for="inputAddress">Nombre</label>
                    <input type="text" class="form-control" v-model="verPerfil.nombre_primero" id="nombre_primero" placeholder="Nombre del">
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="form-group col">
                    <label for="inputAddress2">Apellido</label>
                    <input type="text" class="form-control" v-model="verPerfil.apellido_primero" id="apellido_primero" placeholder="Apellido del juez">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputCity">Máquina / IP</label>
                    <input type="text" class="form-control" v-model="verPerfil.ip" id="ip" placeholder="Dirección de la IP del juez">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control" v-model="verPerfil.ciudad" id="ciudad" placeholder="Ciudad residencia">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputFechaNacimiento">Fecha nacimiento</label>
                    <input type="date" class="form-control" v-model="verPerfil.fecha_nacimiento" id="inputFechaNacimiento">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="cedula">Cédula</label>
                    <input id="cedula" class="form-control" v-model="verPerfil.cedula_id" placeholder="Cédula">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputCity">Whatsapp</label>
                    <input type="text" class="form-control" v-model="verPerfil.whatsapp" id="whatsapp" placeholder="Whatsapp del juez">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="inputState">Correo electrónico</label>
                    <input type="text" class="form-control" v-model="verPerfil.correo" id="correo" placeholder="Correo electrónico">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-12">
                    <label for="acerca">Acerca de {{verPerfil.nombre_primero}} {{verPerfil.apellido_primero}}</label>
                    <textarea class="form-control" v-model="verPerfil.acerca_de_persona" id="acerca" rows="5"></textarea>
                  </div>
                </div>
                <hr class="dotted tall">
  
                <h4 class="mb-3 font-weight-semibold text-dark">Cambiar contraeñas</h4>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputPassword4">Nueva contraseña</label>
                    <input type="password" class="form-control" v-model="verPerfil.password" id="password" placeholder="Escriba la nueva contraseña">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="inputPassword5">Confirme la nueva contraseña</label>
                    <input type="password" class="form-control" id="re_password" placeholder="Confirme la nueva contraseña">
                  </div>
                </div>
                <h4 class="mb-3 font-weight-semibold text-dark">Estado Activo / Inactivo</h4>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputPassword4">Estado</label>
                    <select class="form-control" v-model='verPerfil.estado'>
                      <option value="A">Activo</option>
                      <option value="I">Inactivo</option>
                    </select>
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
  
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 text-end mt-3">
                    <button type="button" class="btn btn-primary modal-confirm" @click="updateDataAsesor">
                      <div class="fas fa-save"></div> Actualizar
                    </button>
                  </div>
                </div>
  
              </form>
  
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
  
        <h4 class="mb-3 mt-0 font-weight-semibold text-dark">Sale Stats</h4>
        <ul class="simple-card-list mb-3">
          <li class="primary">
            <h3>488</h3>
            <p class="text-light">Nullam quris ris.</p>
          </li>
          <li class="primary">
            <h3>$ 189,000.00</h3>
            <p class="text-light">Nullam quris ris.</p>
          </li>
          <li class="primary">
            <h3>16</h3>
            <p class="text-light">Nullam quris ris.</p>
          </li>
        </ul>
  
        <h4 class="mb-3 mt-4 pt-2 font-weight-semibold text-dark">Projects</h4>
        <ul class="simple-bullet-list mb-3">
          <li class="red">
            <span class="title">Porto Template</span>
            <span class="description truncate">Lorem ipsom dolor sit.</span>
          </li>
          <li class="green">
            <span class="title">Tucson HTML5 Template</span>
            <span class="description truncate">Lorem ipsom dolor sit amet</span>
          </li>
          <li class="blue">
            <span class="title">Porto HTML5 Template</span>
            <span class="description truncate">Lorem ipsom dolor sit.</span>
          </li>
          <li class="orange">
            <span class="title">Tucson Template</span>
            <span class="description truncate">Lorem ipsom dolor sit.</span>
          </li>
        </ul>
  
        <h4 class="mb-3 mt-4 pt-2 font-weight-semibold text-dark">Messages</h4>
        <ul class="simple-user-list mb-3">
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joseph Doe Junior" class="rounded-circle">
            </figure>
            <span class="title">Joseph Doe Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joseph Junior" class="rounded-circle">
            </figure>
            <span class="title">Joseph Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joe Junior" class="rounded-circle">
            </figure>
            <span class="title">Joe Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joseph Doe Junior" class="rounded-circle">
            </figure>
            <span class="title">Joseph Doe Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
        </ul>
      </div>
    </div>
    <div class="row" v-show="accion==='registrarPerfil'">
      <div class="col-lg-4 col-xl-3 mb-4 mb-xl-0">
        <section class="card">
          <div class="card-body">
            <div class="thumb-info mb-3">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!logged-user.jpg") ?>" class="rounded img-fluid" alt="John Doe">
              <div class="thumb-info-title">
                <span class="thumb-info-inner">{{registrarPerfil.nombre_primero}} {{registrarPerfil.apellido_primero}}</span>
                <span class="thumb-info-type">Juez</span>
              </div>
            </div>
  
            <div class="widget-toggle-expand mb-3">
              <div class="widget-header">
                <h5 class="mb-2 font-weight-semibold text-dark">Perfil del juez</h5>
                <div class="widget-toggle">+</div>
              </div>
              <div class="widget-content-collapsed">
                <div class="progress progress-xs light">
                  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    100%
                  </div>
                </div>
              </div>
              <div class="widget-content-expanded">
                <ul class="simple-todo-list mt-3">
                  <li class="completed">Update Profile Picture</li>
                  <li class="completed">Change Personal Information</li>
                  <li>Update Social Media</li>
                  <li>Follow Someone</li>
                </ul>
              </div>
            </div>
  
            <hr class="dotted short">
  
            <h5 class="mb-2 mt-3">Acerca del juez</h5>
            <p class="text-2">{{registrarPerfil.acerca_de}}</p>
            <div class="clearfix">
              <a class="text-uppercase text-muted float-end" href="#">(View All)</a>
            </div>
  
            <hr class="dotted short">
  
            <div class="social-icons-list">
              <a rel="tooltip" data-bs-placement="bottom" target="_blank" href="http://www.facebook.com" data-original-title="Facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
              <a rel="tooltip" data-bs-placement="bottom" href="http://www.twitter.com" data-original-title="Twitter"><i class="fab fa-twitter"></i><span>Twitter</span></a>
              <a rel="tooltip" data-bs-placement="bottom" href="http://www.linkedin.com" data-original-title="Linkedin"><i class="fab fa-linkedin-in"></i><span>Linkedin</span></a>
            </div>
  
          </div>
        </section>
      </div>
      <div class="col-lg-8 col-xl-6">
  
        <div class="tabs">
          <ul class="nav nav-tabs tabs-primary">
            <!-- <li class="nav-item">
              <button class="nav-link" data-bs-target="#overview" data-bs-toggle="tab">Overview</button>
            </li> -->
            <li class="nav-item active">
              <button class="nav-link active" data-bs-target="#edit" data-bs-toggle="tab">Registrar información</button>
            </li>
          </ul>
          <div class="tab-content">
            <div id="edit" class="tab-pane active">
              <form class="p-3">
  
                <div class="row row mb-4">
                  <div class="form-group col">
                    <label for="inputAddress">Nombre</label>
                    <input type="text" class="form-control" v-model="registrarPerfil.nombre_primero" id="nombre_primero" placeholder="Nombre del">
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="form-group col">
                    <label for="inputAddress2">Apellido</label>
                    <input type="text" class="form-control" v-model="registrarPerfil.apellido_primero" id="apellido_primero" placeholder="Apellido del juez">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputCity">Máquina / IP</label>
                    <input type="text" class="form-control" v-model="registrarPerfil.ip" id="ip" placeholder="Dirección de la IP del juez">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control" v-model="registrarPerfil.ciudad" id="ciudad" placeholder="Ciudad residencia">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputFechaNacimiento">Fecha nacimiento</label>
                    <input type="date" class="form-control" v-model="registrarPerfil.fecha_nacimiento" id="inputFechaNacimiento">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="cedula">Cédula</label>
                    <input id="cedula" class="form-control" v-model="registrarPerfil.cedula_id" placeholder="Cédula">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputCity">Whatsapp</label>
                    <input type="text" class="form-control" v-model="registrarPerfil.whatsapp" id="whatsapp" placeholder="Whatsapp del juez">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="inputState">Correo electrónico</label>
                    <input type="text" class="form-control" v-model="registrarPerfil.correo" id="correo" placeholder="Correo electrónico">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-12">
                    <label for="acerca">Acerca de {{registrarPerfil.nombre_primero}} {{registrarPerfil.apellido_primero}}</label>
                    <textarea class="form-control" v-model="registrarPerfil.acerca_de_persona" id="acerca" rows="5"></textarea>
                  </div>
                </div>
                <hr class="dotted tall">
  
                <h4 class="mb-3 font-weight-semibold text-dark">Cambiar contraeñas</h4>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputPassword4">Nueva contraseña</label>
                    <input type="password" class="form-control" v-model="registrarPerfil.password" id="password" placeholder="Escriba la nueva contraseña">
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="inputPassword5">Confirme la nueva contraseña</label>
                    <input type="password" class="form-control" id="re_password" placeholder="Confirme la nueva contraseña">
                  </div>
                </div>
                <h4 class="mb-3 font-weight-semibold text-dark">Estado Activo / Inactivo</h4>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="inputPassword4">Estado</label>
                    <select class="form-control" v-model='registrarPerfil.estado'>
                      <option value="A">Activo</option>
                      <option value="I">Inactivo</option>
                    </select>
                  </div>
                  <div class="form-group col-md-6 border-top-0 pt-0">
  
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 text-end mt-3">
                    <button type="button" class="btn btn-warning modal-confirm" @click="saveDataAsesor">
                      <div class="fas fa-save"></div> Registrar
                    </button>
                  </div>
                </div>
  
              </form>
  
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
  
        <h4 class="mb-3 mt-0 font-weight-semibold text-dark">Sale Stats</h4>
        <ul class="simple-card-list mb-3">
          <li class="primary">
            <h3>488</h3>
            <p class="text-light">Nullam quris ris.</p>
          </li>
          <li class="primary">
            <h3>$ 189,000.00</h3>
            <p class="text-light">Nullam quris ris.</p>
          </li>
          <li class="primary">
            <h3>16</h3>
            <p class="text-light">Nullam quris ris.</p>
          </li>
        </ul>
  
        <h4 class="mb-3 mt-4 pt-2 font-weight-semibold text-dark">Projects</h4>
        <ul class="simple-bullet-list mb-3">
          <li class="red">
            <span class="title">Porto Template</span>
            <span class="description truncate">Lorem ipsom dolor sit.</span>
          </li>
          <li class="green">
            <span class="title">Tucson HTML5 Template</span>
            <span class="description truncate">Lorem ipsom dolor sit amet</span>
          </li>
          <li class="blue">
            <span class="title">Porto HTML5 Template</span>
            <span class="description truncate">Lorem ipsom dolor sit.</span>
          </li>
          <li class="orange">
            <span class="title">Tucson Template</span>
            <span class="description truncate">Lorem ipsom dolor sit.</span>
          </li>
        </ul>
  
        <h4 class="mb-3 mt-4 pt-2 font-weight-semibold text-dark">Messages</h4>
        <ul class="simple-user-list mb-3">
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joseph Doe Junior" class="rounded-circle">
            </figure>
            <span class="title">Joseph Doe Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joseph Junior" class="rounded-circle">
            </figure>
            <span class="title">Joseph Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joe Junior" class="rounded-circle">
            </figure>
            <span class="title">Joe Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
          <li>
            <figure class="image rounded">
              <img src="<?php echo site_url("vendor/porto/HTML/img/!sample-user.jpg") ?>" alt="Joseph Doe Junior" class="rounded-circle">
            </figure>
            <span class="title">Joseph Doe Junior</span>
            <span class="message">Lorem ipsum dolor sit.</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <script>
    new Vue({
      el: "#listado_asesores",
      data: {
        asesores: [],
        verPerfil: "",
        registrarPerfil: {
          nombre_primero: "",
          apellido_primero: "",
          fecha_nacimiento: "",
          cedula_id: "",
          ciudad: "",
          whatsapp: "",
          correo: "",
          estado: "",
          acerca_de_persona: "",
          ip: "",
          password: "",
        },
        accion: ""
      },
      mounted: function() {
        this.getDataAsesores();
      },
      methods: {
        async getDataAsesores() {
          let response = await axios.post("", "accion=listarAsesores", {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
          this.accion = "";
          this.asesores = response.data.asesores;
  
        },
        async updateDataAsesor() {
          try {
            let asesor = new FormData();
            asesor.append("nombre_primero", this.verPerfil.nombre_primero);
            asesor.append("apellido_primero", this.verPerfil.apellido_primero);
            asesor.append("fecha_nacimiento", this.verPerfil.fecha_nacimiento);
            asesor.append("correo", this.verPerfil.correo);
            asesor.append("whatsapp", this.verPerfil.whatsapp);
            asesor.append("acerca_de_persona", this.verPerfil.acerca_de_persona);
            asesor.append("cedula_id", this.verPerfil.cedula_id);
            asesor.append("ciudad", this.verPerfil.ciudad);
            asesor.append("ip", this.verPerfil.ip);
            if (this.verPerfil.hasOwnProperty("password")) {
              asesor.append("password", this.verPerfil.password);
            }
            asesor.append("acerca_de_persona", this.verPerfil.acerca_de_persona);
            asesor.append("id", this.verPerfil.id);
            asesor.append("estado", this.verPerfil.estado);
            asesor.append("accion", "actualizarAsesor");
            let response = await axios.post("", asesor, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            });
  
            if (response.data.estado === "error") {
              throw response.data
            }
            MensajeConfirmacionSweetAlert2(response.data);
            this.getDataAsesores();
          } catch (error) {
            MensajeConfirmacionSweetAlert2(error);
          }
  
        },
        async saveDataAsesor() {
          try {
  
            let data = new FormData();
            data.append("nombre_primero", this.registrarPerfil.nombre_primero);
            data.append("apellido_primero", this.registrarPerfil.apellido_primero);
            data.append("ciudad", this.registrarPerfil.ciudad);
            data.append("fecha_nacimiento", this.registrarPerfil.fecha_nacimiento);
            data.append("cedula_id", this.registrarPerfil.cedula_id);
            data.append("whatsapp", this.registrarPerfil.whatsapp);
            data.append("correo", this.registrarPerfil.correo);
            data.append("estado", this.registrarPerfil.estado);
            data.append("ip", this.registrarPerfil.ip);
            data.append("acerca_de_persona", this.registrarPerfil.acerca_de_persona);
            data.append("password", this.registrarPerfil.password);
            data.append("accion", "registrarAsesor");
            let response = await axios.post("", data, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            });
            if (response.data.estado === "error") {
              throw response.data;
            }
            console.log(response.data);
            MensajeConfirmacionSweetAlert2(response.data);
            console.log("entro registrar");
            // this.getDataAsesores();
  //          cleanPerfil();
          } catch (error) {
            console.log(response.data);
            console.log(error);
  
            MensajeConfirmacionSweetAlert2(error);
          }
        },
        verPerfilJuez(i) {
          this.verPerfil = this.asesores[i];
          this.accion = "verPerfil";
          // new PNotify({
          //     title: 'Regular Notice',
          //     text: 'Check me out! I\'m a notice.',
          //     type: 'custom',
          //     addclass: 'notification-primary',
          //     icon: 'fab fa-twitter'
          //   });
        },
        registrarPerfilJuez() {
          this.accion = "registrarPerfil";
          this.verPerfil = "";
        },
        cleanPerfil() {
          this.registrarPerfil.nombre_primero="";
          this.registrarPerfil.apellido_primero="";
          this.registrarPerfil.ciudad="";
          this.registrarPerfil.fecha_nacimiento="";
          this.registrarPerfil.cedula_id="";
          this.registrarPerfil.whatsapp="";
          this.registrarPerfil.correo="";
          this.registrarPerfil.estado="";
          this.registrarPerfil.ip="";
          this.registrarPerfil.acerca_de_persona="";
          this.registrarPerfil.password="";
        }
      }
    })
  </script>