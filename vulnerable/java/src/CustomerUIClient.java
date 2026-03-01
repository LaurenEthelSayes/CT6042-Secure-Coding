import java.io.ObjectInputStream;
import java.io.FileInputStream;
import java.io.ObjectOutputStream;
import java.io.FileOutputStream;
 
public class CustomerUIClient {
	public static void seraializeToFile(Object obj, String filename) {
		try {
			FileOutputStream fos = new FileOutputStream(filename);
			ObjectOutputStream os = new ObjectOutputStream(fos);
			
			System.out.println("Serializing " + obj.toString() + " to " + filename);
			os.writeObject(obj);
			
			os.close();
			fos.close();
		} catch (Exception e) {
			System.out.println("exception : " + e.toString());
		}
	}

    public static void main(String args[]) throws Exception{
        Customer cust = new Customer();
		cust.name = "test";
		cust.address = "uk";
		
		String file = "java/data/Cust.ser";
		
		seraializeToFile(cust, file);
    }
}