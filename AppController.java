ackage controller;

import database.DatabaseConnection;
import model.Documento;
import model.Estudiante;
import model.Notificacion;

import javax.swing.*;
import java.sql.*;

public class AppController {
    private DatabaseConnection dbConnection;

    public AppController() {
        dbConnection = new DatabaseConnection();
        dbConnection.connect();
    }

    // Método para registrar un estudiante
    public void registrarEstudiante(Estudiante estudiante) {
        String sql = "INSERT INTO estudiantes (nombre) VALUES (?)";
        try (PreparedStatement pstmt = dbConnection.getConnection().prepareStatement(sql)) {
            pstmt.setString(1, estudiante.getNombre());
            pstmt.executeUpdate();
            JOptionPane.showMessageDialog(null, "Estudiante registrado.");
        } catch (SQLException e) {
            JOptionPane.showMessageDialog(null, "Error al registrar estudiante: " + e.getMessage());
            e.printStackTrace();
        }
    }

    // Método para ver documentos
    public void verDocumentos(JTextArea txtAreaDocumentos) {
        String sql = "SELECT nombre_archivo FROM documentos";
        try (Statement stmt = dbConnection.getConnection().createStatement(); ResultSet rs = stmt.executeQuery(sql)) {
            txtAreaDocumentos.setText(""); // Limpiar área de texto
            while (rs.next()) {
                txtAreaDocumentos.append(rs.getString("nombre_archivo") + "\n");
            }
        } catch (SQLException e) {
            JOptionPane.showMessageDialog(null, "Error al ver documentos: " + e.getMessage());
            e.printStackTrace();
        }
    }

    // Método para agregar una notificación
    public void agregarNotificacion(Notificacion notificacion, JTextArea txtAreaNotificaciones) {
        String sql = "INSERT INTO notificaciones (mensaje, tipo) VALUES (?, ?)";
        try (PreparedStatement pstmt = dbConnection.getConnection().prepareStatement(sql)) {
            pstmt.setString(1, notificacion.getMensaje());
            pstmt.setString(2, notificacion.getTipo());
            pstmt.executeUpdate();
            JOptionPane.showMessageDialog(null, "Notificación agregada.");
            // Mostrar notificación en el área correspondiente
            txtAreaNotificaciones.append("[" + notificacion.getTipo().toUpperCase() + "] " + notificacion.getMensaje() + "\n");
        } catch (SQLException e) {
            JOptionPane.showMessageDialog(null, "Error al agregar notificación: " + e.getMessage());
            e.printStackTrace();
        }
    }

    // Método para registrar un documento
    public void registrarDocumento(Documento documento) {
        String sql = "INSERT INTO documentos (nombre_archivo, ruta_archivo) VALUES (?, ?)";
        try (PreparedStatement pstmt = dbConnection.getConnection().prepareStatement(sql)) {
            pstmt.setString(1, documento.getNombreArchivo());
            pstmt.setString(2, documento.getRutaArchivo());
            pstmt.executeUpdate();
            JOptionPane.showMessageDialog(null, "Documento registrado.");
        } catch (SQLException e) {
            JOptionPane.showMessageDialog(null, "Error al registrar documento: " + e.getMessage());
            e.printStackTrace();
        }
    }

    public void closeConnection() {
        dbConnection.disconnect();
    }
}

