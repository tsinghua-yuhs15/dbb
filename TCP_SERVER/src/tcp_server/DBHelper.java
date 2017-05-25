package tcp_server;

import java.sql.*;
public class DBHelper {    
		String driver;      
		String url;            
		String user;            
		String password;  
		String name;  
		Connection conn;
		
		public DBHelper(String name,String serverAddress, String user, String password) {
			// TODO Auto-generated constructor stub
			this.driver = "com.mysql.jdbc.Driver";
			this.url = "jdbc:mysql://"+serverAddress+"/"+name; 
			//serverAddress为127.0.0.1:3306
			this.user=user;
			//root
			this.password=password;
			//password
			name=null;
		}
		
		public void DBConnection(){
			try {               
	          	// 加载驱动程序        
	          	Class.forName(driver);  
	          	// 连续数据库       
	          	conn = DriverManager.getConnection(url, user, password);  
	          	if(!conn.isClosed())          
	          		System.out.println("Succeeded connecting to the Database!");  
	          	// statement用来执行SQL语句             
				Statement statement = conn.createStatement();  
	          	// 要执行的SQL语句     
			}   
          	catch(ClassNotFoundException e) {  
          		System.out.println("Sorry,can`t find the Driver!");              
          		e.printStackTrace();  
          	} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}	 
		}
		public boolean sqlExecute(String sql) throws SQLException{
			Statement statement=conn.createStatement();
			boolean r=statement.execute(sql);
			return r;
		}
		public ResultSet sqlExecution(String sql) throws SQLException{
			Statement statement=conn.createStatement();
			ResultSet rs=statement.executeQuery(sql);
			return rs;
		}
}  
