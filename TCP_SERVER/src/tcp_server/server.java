package tcp_server;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.ServerSocket;
import java.net.Socket;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Calendar;
import java.util.Scanner;



public class server {
public static String msg;
public static DBHelper dbh;
public static Calendar calendar;

public static void main(String[] args) throws IOException, InterruptedException, NumberFormatException, SQLException{
	ServerSocket serverSocket=new ServerSocket(6000);
	dbh=new DBHelper("site", "127.0.0.1:3306", "***", "***");
	dbh.DBConnection();
	while(true){
		Socket socket=serverSocket.accept();
		if(socket.isConnected()){
			System.out.println("Connection Built");
			System.out.println(socket.getInetAddress());
			System.out.println(socket.getRemoteSocketAddress());
		}
	Server_thread st1,st2,st3;
	st1=new Server_thread(0, socket);
	st2=new Server_thread(1, socket);
	st3=new Server_thread(2, socket);
	st1.start();
	st2.start();
	st3.start();
	//socket.close();
	} 
}
}
