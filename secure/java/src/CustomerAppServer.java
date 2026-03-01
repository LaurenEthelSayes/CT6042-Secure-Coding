import java.io.*;

public class CustomerAppServer {
    static Object obj;

    public static Object deseraializeFromFile(String filename) {
        try {
            FileInputStream fin = new FileInputStream(filename);
            ObjectInputStream in = new ObjectInputStream(fin);

            System.out.println("Deserializing from " + filename);
            obj = in.readObject();

            in.close();
            fin.close();
        } catch (Exception e) {
            System.out.println("exception : " + e.toString());
        }
        return obj;
    }

    public static void main(String args[]) throws Exception{
        String file = (args.length > 0) ? args[0] : "java/data/Cust.ser";
        deseraializeFromFile(file);
    }
}