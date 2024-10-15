package view;

import controller.AppController;
import model.Documento;
import model.Estudiante;
import model.Notificacion;

import javax.swing.*;
import java.awt.*;
import java.io.File;

public class TallerProgramacionApp extends JFrame {
    private JTextField txtUsuario;
    private JPasswordField txtContrasena;
    private JTextField txtNombreEstudiante;
    private JTextArea txtAreaDocumentos;
    private JTextArea txtAreaNotificaciones;
    private AppController appController;

    public TallerProgramacionApp() {
        appController = new AppController();
        setTitle("Taller de Programación");
        setSize(600, 500);
        setDefaultCloseOperation(EXIT_ON_CLOSE);
        setLocationRelativeTo(null);

        // Crear panel de login
        JPanel panelLogin = new JPanel();
        panelLogin.setLayout(new GridLayout(3, 2));
        panelLogin.add(new JLabel("Usuario:"));
        txtUsuario = new JTextField();
        panelLogin.add(txtUsuario);
        panelLogin.add(new JLabel("Contraseña:"));
        txtContrasena = new JPasswordField();
        panelLogin.add(txtContrasena);
        JButton btnLogin = new JButton("Iniciar Sesión");
        panelLogin.add(btnLogin);
        add(panelLogin, BorderLayout.NORTH);

        // Crear panel de estudiante
        JPanel panelEstudiante = new JPanel();
        panelEstudiante.setLayout(new GridLayout(3, 2));
        panelEstudiante.add(new JLabel("Nombre Estudiante:"));
        txtNombreEstudiante = new JTextField();
        panelEstudiante.add(txtNombreEstudiante);
        JButton btnRegistrarEstudiante = new JButton("Registrar Estudiante");
        panelEstudiante.add(btnRegistrarEstudiante);
        JButton btnVerDocumentos = new JButton("Ver Documentos");
        panelEstudiante.add(btnVerDocumentos);
        JButton btnAgregarNotificacion = new JButton("Agregar Notificación");
        panelEstudiante.add(btnAgregarNotificacion);
        JButton btnSubirDocumento = new JButton("Subir Documento");
        panelEstudiante.add(btnSubirDocumento);
        add(panelEstudiante, BorderLayout.CENTER);

        // Crear panel de documentos
        txtAreaDocumentos = new JTextArea(10, 30);
        txtAreaDocumentos.setEditable(false);
        JScrollPane scrollDocumentos = new JScrollPane(txtAreaDocumentos);
        add(scrollDocumentos, BorderLayout.WEST);

        // Crear panel de notificaciones
        txtAreaNotificaciones = new JTextArea(10, 30);
        txtAreaNotificaciones.setEditable(false);
        JScrollPane scrollNotificaciones = new JScrollPane(txtAreaNotificaciones);
        add(scrollNotificaciones, BorderLayout.EAST);

        // Botones de acción
        btnLogin.addActionListener(e -> {
            String usuario = txtUsuario.getText();
            String contrasena = new String(txtContrasena.getPassword());
            // Aquí puedes agregar lógica para verificar el usuario y la contraseña
            JOptionPane.showMessageDialog(null, "Inicio de sesión exitoso");
        });

        btnRegistrarEstudiante.addActionListener(e -> {
            String nombre = txtNombreEstudiante.getText();
            Estudiante estudiante = new Estudiante(nombre);
            appController.registrarEstudiante(estudiante);
        });

        btnVerDocumentos.addActionListener(e -> appController.verDocumentos(txtAreaDocumentos));

        btnAgregarNotificacion.addActionListener(e -> {
            String mensaje = JOptionPane.showInputDialog("Ingrese la notificación:");
            String tipo = JOptionPane.showInputDialog("Ingrese el tipo de notificación (examen/tarea):");
            Notificacion notificacion = new Notificacion(mensaje, tipo);
            appController.agregarNotificacion(notificacion, txtAreaNotificaciones);
        });

        btnSubirDocumento.addActionListener(e -> subirDocumento());

        setVisible(true);
    }

    // Método para subir un documento
    private void subirDocumento() {
        JFileChooser fileChooser = new JFileChooser();
        int returnValue = fileChooser.showOpenDialog(this);
        if (returnValue == JFileChooser.APPROVE_OPTION) {
            File selectedFile = fileChooser.getSelectedFile();
            Documento documento = new Documento(selectedFile.getName(), selectedFile.getAbsolutePath());
            appController.registrarDocumento(documento);
        }
    }

    public static void main(String[] args) {
        new TallerProgramacionApp();
    }
}

