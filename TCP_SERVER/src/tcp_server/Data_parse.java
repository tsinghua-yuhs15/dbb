package tcp_server;


import java.sql.ResultSet;
import java.sql.SQLException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

public class Data_parse {
public static void Rx_data_parse(byte[] b){
	String temp,sql;
	temp=byte_bit_transition.byteToBit(b[0]);
	String msg_sort=temp.substring(0, 3);
	String msg_subsort=temp.substring(3);
	byte msg_sort_byte=byte_bit_transition.BitToByte(msg_sort);
	byte msg_subsort_byte=byte_bit_transition.BitToByte(msg_subsort);
	//监测序号
	int[] bb = new int[20];
	for(int i=0;i<20;i++){
		bb[i]=((int)(b[i])+256)%256;
	}
	int msg_index = b[1]*256+b[2];
	//秒数
	int second = b[3]*256+b[4];
	

	
	//电池柜编号
	int box_id = bb[5];
	//固定位00000011
	if(bb[6]!=3){
		//包错误
		return;
	}
	if(msg_sort_byte==1){
		//定时检测
		if(bb[7]!=10){
			//有效数据位固定
			return;
		}
		//采集电信号
		System.out.println(bb[8]);
		System.out.println(bb[9]);
		System.out.println((double)(bb[8]*256+bb[9])/100);
		System.out.println((bb[10]*256+bb[11])/1000);
		double voltage = (double)(bb[8]*256+bb[9])/100,
				current = (double)(bb[10]*256+bb[11])/1000,
				power = (double)(bb[12]*256+bb[13])/10,
				energy = (double)(((bb[14]*256+bb[15])*256+bb[16])*256+bb[17])/100;
		switch(msg_subsort_byte){
			case 0:{
				//断开
			}
			case 1:{
				//恒压
			}
			case 2:{
				//恒流
			}
			case 3:{
				//涓流
			}
			case 4:{
				//充电完成
				sql = "INSERT INTO charge_log (box_id,cell_id,time,event) VALUES ("+box_id+"),1,sysdate(),2";
				String search_user = "SELECT user_id FROM charge_log WHERE event='0' AND box_id='"+box_id+
						"' order by id DESC limit 1;",name_tel_info;
				try {
					server.dbh.sqlExecute(sql);
					ResultSet rSet = server.dbh.sqlExecution(search_user);
					String user_id=null;
					if(rSet.next()){
						user_id = rSet.getString("user_id");
					}
					name_tel_info = "SELECT name,tel FROM user WHERE user_id='"+user_id+"';";
					rSet = server.dbh.sqlExecution(name_tel_info);
					if(rSet.next()){
						String name = rSet.getString("name");
						String tel = rSet.getString("tel");
						String context = "尊敬的"+name+"，您好。您在#1柜的电池充电已完成，请尽快前往取下电池。";
						short_message.sendMsg(context, tel);
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (Exception e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			//预留
		}
		try {
			if(server.calendar!=null){
				sql = "INSERT INTO monitor_record (time,exist_abnormal,box_id,"
					+"V"+","+"A"+","+"W"+","+"Wh"+") "+
						"values(sysdate(),'"+"0','"+box_id+"','"+voltage+"','"+current+"','"+power+"','"+energy+"')";
			}
			else{
				sql = "INSERT INTO monitor_record (time,exist_abnormal,box_id,"
						+"V"+","+"A"+","+"W"+","+"Wh"+") "+
							"values(sysdate(),'"+"0','"+box_id+"','"+voltage+"','"+current+"','"+power+"','"+energy+"')";
			}
			System.out.println(sql);
			server.dbh.sqlExecute(sql);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	else if(msg_sort_byte==2){
		//单片机常规消息
		switch(msg_subsort_byte){
		case 0:{
			//单片机开机
			//Module_Operation.time_reset(box_id);
			break;
		}
		case 1:{
			//时钟重置
			//Module_Operation.time_reset(box_id);
			break;
		}
		case 2:{
			//单片机关机
		}
		//预留
	}
	}
	else if(msg_sort_byte==3){
		//突发消息
		switch(msg_subsort_byte){
		case 0:{
			//开始充电
		}
		case 1:{
			//进入恒压
		}
		case 2:{
			//进入恒流
		}
		case 3:{
			//进入涓流
		}
		case 4:{
			//充电完成
		}
		case 5:{
			//取消充电
		}
		case 6:{
			//电池连接
			double voltage = (double)(bb[8]*256+bb[9])/100,
					current = (double)(bb[10]*256+bb[11])/1000,
					power = (double)(bb[12]*256+bb[13])/10,
					energy = (double)(((bb[14]*256+bb[15])*256+bb[16])*256+bb[17])/100;
			sql = "INSERT INTO charge_log (box_id,cell_id,time,event,voltage,capacity) "
					+ "VALUES ('"+box_id+"',1,sysdate(),1,'"+voltage+"','"+energy+"')";
			try {
				server.dbh.sqlExecute(sql);
				System.out.println(sql);
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		case 7:{
			//电池识别完成
		}
		case 8:{
			//柜门被打开
		}
		case 9:{
			//柜门被关闭
		}
		//预留
		}
	}
	else if(msg_sort_byte==4){
		//ACK消息
		//响应序号
		int response_index=bb[7],
				response_status=bb[8];
	}
	//b[18],b[19]校验处理
	//CRC??
	//写入数据库操作
}
public static byte[] Tx_data_parse(int msg_type, int msg_subtype, double elec_paras, int box_id,int instruction_index){
	byte[] Rx_msg = new byte[13];
	String msg_type_str=byte_bit_transition.byteToBit((byte)msg_type);
	String msg_subtype_str=byte_bit_transition.byteToBit((byte)msg_subtype);
	String msg_head = msg_type_str.substring(5,8)+msg_subtype_str.substring(3, 8);
	Rx_msg[0]=byte_bit_transition.BitToByte(msg_head);
	int inst_high=instruction_index/256;
	int inst_low=instruction_index%256;
	Rx_msg[1]=(byte)inst_high;
	Rx_msg[2]=(byte)inst_low;
	Rx_msg[3]=(byte)box_id;
	if(msg_type==1){
		//充电控制
		switch(msg_subtype){
		case 0:{
			//立即断开
			Rx_msg[4]=0;
			Rx_msg[5]=0;
		}
		case 1:{
			//恒压充电
			int voltage = (int) (elec_paras*100);
			int v_high = voltage/256;
			int v_low = voltage%256;
			Rx_msg[4]=(byte) v_high;
			Rx_msg[5]=(byte)v_low;
			break;
		}
		case 2:{
			//恒流充电
			int cuurent = (int) (elec_paras*1000);
			int c_high = cuurent/256;
			int c_low = cuurent%256;
			Rx_msg[4]=(byte)c_high;
			Rx_msg[5]=(byte)c_low;
			break;
		}
		case 3:{
			//涓流充电
			int s_cuurent = (int) (elec_paras*1000);
			int c_high = s_cuurent/256;
			int c_low = s_cuurent%256;
			Rx_msg[4]=(byte) c_high;
			Rx_msg[5]=(byte)c_low;
			break;
		}
		}
	}
	else if(msg_type==2){
		//单片机控制
		switch(msg_subtype){
		case 0:{
			//请求数据
			Rx_msg[4]=0;
			Rx_msg[5]=0;
			break;
		}
		case 1:{
			//重启
		}
		}
	}
	else if(msg_type==3){
		//柜门控制
		Rx_msg[4]=0;
		Rx_msg[5]=0;
	}


	
	//存疑，取消负位，手动转unsigned
	byte[] ck = new byte[14];
	for(int i=0;i<Rx_msg.length-2;i++){
		ck[i] = Rx_msg[i];
		if(Rx_msg[i]<0){
			Rx_msg[i]+=256;
			ck[i] = (byte) (0-ck[i]);
		}
	}
	//管理员密钥
	ck[11] = 't';
	ck[12] = 'z';
	ck[13] = 'z';
	//校验位

	String unchecked = new String(ck);
	String checked = MD5_check.getMd5(unchecked);
	String c1 = checked.substring(0, 2),c2 = checked.substring(2,4);
	int p1 = Integer.parseInt(c1,16),p2 = Integer.parseInt(c2,16);
	Rx_msg[11] = (byte) (p1%128);
	Rx_msg[12] = (byte) (p2%128);
	return Rx_msg;
}
}

//byte-bit转换方法类
class byte_bit_transition{
	
	public static String byteToBit(byte b) {  
	    return "" +(byte)((b >> 7) & 0x1) +   
	    (byte)((b >> 6) & 0x1) +   
	    (byte)((b >> 5) & 0x1) +   
	    (byte)((b >> 4) & 0x1) +   
	    (byte)((b >> 3) & 0x1) +   
	    (byte)((b >> 2) & 0x1) +   
	    (byte)((b >> 1) & 0x1) +   
	    (byte)((b >> 0) & 0x1);  
	}  
	
	public static byte BitToByte(String byteStr) {  
	    int re; 
	    if (null == byteStr) {  
	        return 0;  
	    }   
	    re = Integer.parseInt(byteStr, 2);
	    return (byte) re;  
	}  
}

//单片机操作类
class Module_Operation{
	public static void time_reset(int box_id){
		server.calendar = Calendar.getInstance();
		String sql = "INSERT INTO time_sync_record (module_id, time) VALUES("+box_id+", sysdate());";
		try {
			server.dbh.sqlExecute(sql);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}