package org.develnext.jphp.ext.jcef.classes;

import org.cef.browser.CefBrowser;
import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Setter;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

import javax.swing.*;
import java.awt.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

@Reflection.Name("CefBrowserWindow")
@Reflection.Namespace(JCefExtension.NS)
public class PCefBrowserWindow extends BaseObject {
    private Window frame;
    private boolean alwaysOnTop = false;

    public PCefBrowserWindow(Environment env, Window wrappedObject) {
        super(env);
        frame = wrappedObject;
    }

    public PCefBrowserWindow(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(CefBrowser browser) {
        __construct(browser, false);
    }

    @Signature
    public void __construct(CefBrowser browser, boolean asDialog) {
        if (asDialog) {
            JDialog jDialog = new JDialog();
            jDialog.getContentPane().add(browser.getUIComponent());
            jDialog.setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);

            this.frame = jDialog;
        } else {
            JFrame jframe = new JFrame();
            jframe.getContentPane().add(browser.getUIComponent());
            jframe.setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);

            frame = jframe;
        }

        frame.pack();
        frame.setSize(800, 600);
        frame.setLocationRelativeTo(null);

    }

    @Setter
    public void setSize(int[] size) {
        if (size.length >= 2) {
            frame.setSize(size[0], size[1]);
        }
    }

    @Getter
    public int[] getSize() {
        return new int[]{frame.getSize().width, frame.getSize().height};
    }

    @Setter
    public void setPosition(int[] pos) {
        if (pos.length >= 2) {
            frame.setLocation(pos[0], pos[1]);
        }
    }

    @Getter
    public int[] getPosition() {
        Point location = frame.getLocation();
        return new int[]{location.x, location.y};
    }

    @Getter
    public String getTitle() {
        if (frame instanceof JDialog) {
            return ((JDialog) frame).getTitle();
        } else if (frame instanceof JFrame) {
            return ((JFrame) frame).getTitle();
        }

        return null;
    }

    @Setter
    public void setTitle(String title) {
        if (frame instanceof JDialog) {
            ((JDialog) frame).setTitle(title);
        } else if (frame instanceof JFrame) {
            ((JFrame) frame).setTitle(title);
        }
    }

    @Getter
    public boolean getAlwaysOnTop() {
        return alwaysOnTop;
    }

    @Setter
    public void setAlwaysOnTop(boolean value) {
        frame.setAlwaysOnTop(value);
        this.alwaysOnTop = value;
    }

    @Getter
    public Window.Type getType() {
        return frame.getType();
    }

    @Setter
    public void setType(Window.Type type) {
        frame.setType(type);
    }

    @Signature
    public void show() {
        frame.setVisible(true);
    }

    @Signature
    public void hide() {
        frame.setVisible(false);
    }

    @Signature
    public void free() {
        frame.dispose();
    }

    @Signature
    public void onClosed(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosed(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onClosing(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onOpened(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowOpened(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onIconified(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowIconified(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onDeiconified(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowDeiconified(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public static PCefBrowserWindow find(Environment env, CefBrowser browser) {
        Component uiComponent = browser.getUIComponent();
        Window window = SwingUtilities.getWindowAncestor(uiComponent);

        if (window != null) {
            return new PCefBrowserWindow(env, window);
        }

        return null;
    }
}
