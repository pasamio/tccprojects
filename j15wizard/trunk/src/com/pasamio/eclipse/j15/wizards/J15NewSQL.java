package com.pasamio.eclipse.j15.wizards;

import org.eclipse.jface.viewers.IStructuredSelection;
import org.eclipse.jface.wizard.Wizard;
import org.eclipse.ui.INewWizard;
import org.eclipse.ui.IWorkbench;
import org.eclipse.core.runtime.*;
import org.eclipse.jface.operation.*;
import java.lang.reflect.InvocationTargetException;
import org.eclipse.jface.dialogs.MessageDialog;
import org.eclipse.jface.viewers.ISelection;
import org.eclipse.core.resources.*;
import org.eclipse.core.runtime.CoreException;
import java.io.*;
import org.eclipse.ui.*;
import org.eclipse.ui.ide.IDE;
import java.util.Calendar;
import java.util.TimeZone;


/**
 * This is a sample new wizard. Its role is to create a new file 
 * resource in the provided container. If the container resource
 * (a folder or a project) is selected in the workspace 
 * when the wizard is opened, it will accept it as the target
 * container. The wizard creates one file with the extension
 * "php". If a sample multi-page editor (also available
 * as a template) is registered for the same extension, it will
 * be able to open it.
 */

public class J15NewSQL extends Wizard implements INewWizard {
	private J15NewSQLPage page;
	private ISelection selection;

	/**
	 * Constructor for J15NewSQLFile.
	 */
	public J15NewSQL() {
		super();
		setNeedsProgressMonitor(true);
	}
	
	/**
	 * Adding the page to the wizard.
	 */

	public void addPages() {
		page = new J15NewSQLPage(selection);
		addPage(page);
	}

	/**
	 * This method is called when 'Finish' button is pressed in
	 * the wizard. We will create an operation and run it
	 * using wizard as execution context.
	 */
	public boolean performFinish() {
		final String containerName = page.getContainerName();
		final String fileName = page.getFileName();
		final String title = page.getDescription();
		final String phpVer = page.getPHPVersion();
		IRunnableWithProgress op = new IRunnableWithProgress() {
			public void run(IProgressMonitor monitor) throws InvocationTargetException {
				try {
					doFinish(containerName, fileName, title, phpVer, monitor);
				} catch (CoreException e) {
					throw new InvocationTargetException(e);
				} finally {
					monitor.done();
				}
			}
		};
		try {
			getContainer().run(true, false, op);
		} catch (InterruptedException e) {
			return false;
		} catch (InvocationTargetException e) {
			Throwable realException = e.getTargetException();
			MessageDialog.openError(getShell(), "Error", realException.getMessage());
			return false;
		}
		return true;
	}
	
    /**
     * The worker method. It will find the container, create the file if missing
     * or just replace its contents, and open the editor on the newly created
     * file.
     */
    private void doFinish(String containerName, String fileName, String title,
    		String phpVer,
            IProgressMonitor monitor) throws CoreException {

        monitor.beginTask("Creating " + fileName, 2);
        IWorkspaceRoot root = ResourcesPlugin.getWorkspace().getRoot();
        IResource resource = root.findMember(new Path(containerName));

        if (!resource.exists() || !(resource instanceof IContainer)) {
            throwCoreException("Container \"" + containerName
                    + "\" does not exist.");
        }
        IContainer container = (IContainer) resource;

        final IFile file = container.getFile(new Path(fileName));
        try {

            InputStream stream = openContentStream(title,phpVer);

            try {
                if (file.exists()) {
                    file.setContents(stream, true, true, monitor);
                } else {
                    file.create(stream, true, monitor);
                }
            } finally {
                stream.close();
            }

        } catch (IOException e) {
        }
        monitor.worked(1);
        monitor.setTaskName("Opening file for editing...");
        getShell().getDisplay().asyncExec(new Runnable() {
            public void run() {
                IWorkbenchPage page = PlatformUI.getWorkbench()
                        .getActiveWorkbenchWindow().getActivePage();
                try {
                    IDE.openEditor(page, file, true);
                } catch (PartInitException e) {
                }
            }
        });
        monitor.worked(1);
	}

	
	/**
	 * We will initialize file contents with a sample text.
	 */

    public static InputStream openContentStream(
    			String description, String phpVer
    ) throws CoreException {
        final String newline = "\n"; // System.getProperty("line.separator");
        String line;
        StringBuffer sb = new StringBuffer();
        Calendar cal = Calendar.getInstance(TimeZone.getDefault()); 
        String DATE_FORMAT = "yyyy-MM-dd"; 
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat(DATE_FORMAT);
        sdf.setTimeZone(TimeZone.getDefault()); 
        String currentDate = sdf.format(cal.getTime());
        try {
            InputStream input = J15NewSQL.class.getResourceAsStream(
                    "templates/blank-model.template");
            BufferedReader reader = new BufferedReader(new InputStreamReader(
                    input));
            try {

                while ((line = reader.readLine()) != null) {
                    line = line.replaceAll("\\$\\{docdescription\\}", description);
                    line = line.replaceAll("\\$\\{date\\}", currentDate);
                    line = line.replaceAll("\\$\\{phpver\\}", phpVer);
                    sb.append(line);
                    sb.append(newline);
                }

            } finally {
                reader.close();
            }

        } catch (IOException ioe) {
            IStatus status = new Status(IStatus.ERROR, "J15Wizard", IStatus.OK,
                    ioe.getLocalizedMessage(), null);
            throw new CoreException(status);
        }

        return new ByteArrayInputStream(sb.toString().getBytes());
    	
		//String contents =
		//	"This is the initial file contents for *.php file that should be word-sorted in the Preview page of the multi-page editor";
		//return new ByteArrayInputStream(contents.getBytes());
		//return this.getClass().getResourceAsStream("templates/blank-model.template");
	}

	private void throwCoreException(String message) throws CoreException {
		IStatus status =
			new Status(IStatus.ERROR, "J15Wizard", IStatus.OK, message, null);
		throw new CoreException(status);
	}

	/**
	 * We will accept the selection in the workbench to see if
	 * we can initialize from it.
	 * @see IWorkbenchWizard#init(IWorkbench, IStructuredSelection)
	 */
	public void init(IWorkbench workbench, IStructuredSelection selection) {
		this.selection = selection;
	}
}