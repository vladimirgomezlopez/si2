package model;

public class Estudiante {
    private int id;
    private String nombre;

    public Estudiante(String nombre) {
        this.nombre = nombre;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNombre() {
        return nombre;
    }
}
