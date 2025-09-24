<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Gestión de Responsables</h2>

    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-5xl mx-auto">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Código</th>
            <th>Documento</th>
            <th>Nombres y Apellidos</th>
            <th>Cargo</th>
            <th>Teléfono</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in responsables" :key="r.id">
            <td>{{ r.id }}</td>
            <td>{{ r.Documento }}</td>
            <td>{{ r["Nombre y Apellidos"] }}</td>
            <td>{{ r.Cargo }}</td>
            <td>{{ r.Telefono }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: "ResponsablesView",
  data() {
    return {
      responsables: []
    }
  },
  mounted() {
    fetch("http://localhost/responsables/")
      .then(res => res.json())
      .then(data => {
        console.log("Datos recibidos:", data)
        this.responsables = data
      })
      .catch(err => console.error("Error al cargar responsables:", err))
  }
}
</script>
