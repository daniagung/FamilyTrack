package biz.prolike.hereiam.adapters;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import biz.prolike.hereiam.R;
import biz.prolike.hereiam.containers.ChildData;

/**
 * Created by admin on 10/12/15.
 */
public class ChildAdapter extends ArrayAdapter<ChildData> {

    private List<ChildData> listdata=null;
    private LayoutInflater mInflater=null;
    public ChildAdapter(Activity context, List<ChildData> calldata) {
        super(context, 0);
        this.listdata=calldata;
        mInflater = context.getLayoutInflater();
    }


    @Override
    public int getCount() {
        return listdata.size();
    }

    @Override
    public long getItemId(int position) {
        ChildData cc = listdata.get(position);
        int foo = Integer.parseInt(cc.getId());
        return  foo;
    }

    public View getView(int position, View convertView, ViewGroup parent) {

        final ViewHolder holder;

        if (convertView == null || convertView.getTag() == null) {
            holder = new ViewHolder();
            convertView = mInflater.inflate(R.layout.child_item, null);

            holder.imageView = (TextView) convertView.findViewById(R.id.imageView);
            holder.textView = (TextView) convertView.findViewById(R.id.textView);
            holder.mail = (TextView) convertView.findViewById(R.id.mail);
            holder.textView2 = (TextView) convertView.findViewById(R.id.textView2);
            holder.textView3 = (TextView) convertView.findViewById(R.id.textView3);
            holder.textView4 = (TextView) convertView.findViewById(R.id.textView4);
            convertView.setTag(holder);
        }
        else {
            holder = (ViewHolder) convertView.getTag();
        }

        ChildData calldatalist = listdata.get(position);
        String name=calldatalist.getName();
        String email =calldatalist.getEmail();
        String photo= calldatalist.getPhoto();
        //int calldurations = Integer.parseInt(callduration);
        String lette = "";

        holder.textView.setText(name);
        holder.mail.setText(email);
        lette = name.substring(0,1);
        holder.imageView.setText(lette);


        return convertView;
    }

    private static class ViewHolder {
        TextView imageView;
        TextView textView;
        TextView mail;
        TextView textView2;
        TextView textView3;
        TextView textView4;
    }


    private String secondsToString(int pTime) {
        return String.format("%02d:%02d", pTime / 60, pTime % 60);
    }

    public static String ConvertDate(Date date) {
        String dateForMySql = "";
        if (date == null) {
            dateForMySql = null;
        } else {
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            dateForMySql = sdf.format(date);
        }

        return dateForMySql;
    }
}