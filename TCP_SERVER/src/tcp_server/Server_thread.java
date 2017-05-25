package tcp_server;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.Socket;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Scanner;

public class Server_thread extends Thread{
	int kind;
	Socket socket;
	int rx_index = 0;
	int tx_index = 0;
	int instruction_index = 1;
	boolean tx_flag;
	byte[] bs;
	public Server_thread(int kind,Socket socket) {
		// TODO Auto-generated constructor stub
		super();
		this.kind=kind;
		this.socket=socket;
		tx_flag=false;
	}
	public void run(){
		if(kind==0){
			//普通发送线程
			System.out.println("normal thread start!");
			try {
				OutputStream os=socket.getOutputStream();
				do {
					String string;
					
					//测试用
					Scanner scanner=new Scanner(System.in);
					string=scanner.nextLine();
					//
					
					os.write(string.getBytes());
					sleep(1000);
				} while (true);
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		if(kind==1){
			//接收线程
			System.out.println("receiving thread start!");
			try {
				InputStream is=socket.getInputStream();
				
				do {
					bs = new byte[128];
					is.read(bs);
					if(bs[0] == -1){
						for(int i=0;i<20;i++){
							bs[i] = bs[i+1];
						}
					}
					//
					int i=0;
					System.out.println("input mesg::");
					while(i<20){
						int bbs = ((int)(bs[i])+256)%256;
						System.out.print(Integer.toHexString(bbs));
						System.out.print(" ");
						i++;
					}
					System.out.println();
					//
					Data_parse.Rx_data_parse(bs);
					//
				} while (true);
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		if(kind==2){
			//php指令读取并发送线程
			String sql,update,type_str,subtype_str,box_id_str,paras_str;
			int type,subtype,box_id;
			double paras;
			System.out.println("php_inst thread start!");
			while(socket==null){}
			try {
				OutputStream os=socket.getOutputStream();
				do {
					sleep(500);
					sql = "SELECT * FROM instruction_from_php_to_java WHERE status='1'";
					update = "UPDATE instruction_from_php_to_java SET status='0' WHERE status='1'";
					ResultSet rs = server.dbh.sqlExecution(sql);
					System.out.println("start search");
					if(rs.next()){
						type_str=rs.getString("type");
						subtype_str=rs.getString("subtype");
						box_id_str=rs.getString("box_id");
						paras_str=rs.getString("paras");
						type = Integer.parseInt(type_str);
						subtype = Integer.parseInt(subtype_str);
						box_id = Integer.parseInt(box_id_str);
						paras = Double.parseDouble(paras_str);
						byte[] mString = Data_parse.Tx_data_parse(type, subtype, paras, box_id, instruction_index);
						String string = new String(mString);
						
						//
						System.out.println("output mesg::");
						for(int i=0;i<13;i++){
							System.out.print(Integer.toHexString(mString[i]));
							System.out.print(" ");
						}
						System.out.println();
						//
						
						os.write(string.getBytes());
						instruction_index++;
						server.dbh.sqlExecute(update);
					}
					else{
						continue;
					}
					System.out.println("end search");
					//
					sleep(500);
					//
				} while (true);
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (SQLException e) {
				// TODO: handle exception
			}
		}
	}
}
