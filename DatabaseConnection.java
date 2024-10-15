package database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConnection {
    private String dbURL = "jdbc:postgresql://localhost:5432/taller_programacion";
    private String dbUser = "postgres";  // Cambia por tu usuario de PostgreSQL
    private String dbPassword = "fernando";  // Cambia por tu contraseña de PostgreSQL
    private Connection connection;

    public Connection connect() {
        try {
            Class.forName("org.postgresql.Driver");
            connection = DriverManager.getConnection(dbURL, dbUser, dbPassword);
            return connection;
        } catch (SQLException | ClassNotFoundException e) {
            e.printStackTrace();
            return null;
        }
    }

    public void disconnect() {
        try {
            if (connection != null && !connection.isClosed()) {
                connection.close();
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public Connection getConnection() {
        return connection;
    }
}

